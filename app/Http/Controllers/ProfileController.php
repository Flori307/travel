<?php
// app/Http/Controllers/ProfileController.php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // Показать профиль
    public function show()
    {
        $client = Auth::user();
        
        // Берем только последние 3 бронирования для профиля
        $bookings = $client->bookings()
            ->with('tour.hotel')
            ->latest()
            ->limit(3)
            ->get();
            
        $reviews = $client->reviews()
            ->with('tour')
            ->latest()
            ->get();

        return view('profile.show', compact('client', 'bookings', 'reviews'));
    }

    // ... остальные методы остаются без изменений ...
    public function edit()
    {
        $client = Auth::user();
        return view('profile.edit', compact('client'));
    }

    public function update(Request $request)
    {
        $client = Auth::user();

        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'email' => 'required|email|unique:clients,email,' . $client->client_id . ',client_id',
            'phone' => 'required|string|max:20',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $client->update($request->only(['first_name', 'last_name', 'email', 'phone']));

        return redirect()->route('profile.show')
            ->with('success', 'Профиль успешно обновлен!');
    }

    public function showChangePasswordForm()
    {
        return view('profile.change-password');
    }

    public function changePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'current_password' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $client = Auth::user();

        if (!Hash::check($request->current_password, $client->password)) {
            return redirect()->back()
                ->withErrors(['current_password' => 'Текущий пароль неверен.'])
                ->withInput();
        }

        $client->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('profile.show')
            ->with('success', 'Пароль успешно изменен!');
    }
}