<?php
// app/Http/Controllers/AuthController.php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function showRegisterForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'email' => 'required|email|unique:clients,email',
            'phone' => 'required|string|max:20',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $client = Client::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'registration_date' => now(),
            'role' => 'user',
        ]);

        Auth::login($client);

        return redirect()->route('home')
            ->with('success', 'Регистрация прошла успешно! Добро пожаловать!');
    }

    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
{
    $credentials = $request->validate([
        'email' => 'required|email',
        'password' => 'required|string',
    ]);

    // ОТЛАДКА 1: Проверяем, находит ли пользователя
    $client = Client::where('email', $credentials['email'])->first();
    \Log::info('Пользователь найден:', ['id' => $client->client_id ?? 'не найден', 'email' => $credentials['email']]);
    
    if (Auth::attempt($credentials, $request->filled('remember'))) {
        // ОТЛАДКА 2: Проверяем, авторизовался ли
        \Log::info('Auth::attempt успешен, пользователь авторизован:', ['id' => Auth::id()]);
        $request->session()->regenerate();
        
        // ОТЛАДКА 3: Проверяем сессию
        \Log::info('ID сессии после регенерации:', ['session_id' => session()->getId()]);
        
        return redirect()->intended(route('home'))
            ->with('success', 'Добро пожаловать!');
    }

    \Log::info('Auth::attempt НЕ успешен');
    return back()->withErrors([
        'email' => 'Неверный email или пароль.',
    ])->onlyInput('email');
}

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('home')
            ->with('success', 'Вы успешно вышли из системы.');
    }
}