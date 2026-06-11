<!-- resources/views/auth/login.blade.php -->
@extends('layouts.app')

@section('title', 'Вход - TravelDream')

@section('content')
<div class="min-h-screen bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md mx-auto">
        <div class="text-center">
            <i class="fas fa-plane-departure text-4xl text-blue-600 mb-4"></i>
            <h2 class="text-3xl font-bold text-gray-900 mb-2">Вход в аккаунт</h2>
            <p class="text-gray-600">Добро пожаловать обратно!</p>
        </div>

        <div class="mt-8 bg-white py-8 px-6 shadow rounded-lg">
            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" 
                           class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('email') border-red-500 @enderror">
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mt-4">
                    <label for="password" class="block text-sm font-medium text-gray-700">Пароль</label>
                    <input type="password" id="password" name="password" 
                           class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('password') border-red-500 @enderror">
                    @error('password')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mt-4 flex items-center justify-between">
                    <div class="flex items-center">
                        <input type="checkbox" id="remember" name="remember" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <label for="remember" class="ml-2 block text-sm text-gray-700">Запомнить меня</label>
                    </div>
                </div>

                <div class="mt-6">
                    <button type="submit" class="w-full bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition">
                        <i class="fas fa-sign-in-alt mr-2"></i>Войти
                    </button>
                </div>
            </form>

            <div class="mt-6 text-center">
                <p class="text-gray-600">
                    Нет аккаунта?
                    <a href="{{ route('register') }}" class="text-blue-600 hover:text-blue-500 font-medium">
                        Зарегистрируйтесь здесь
                    </a>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection