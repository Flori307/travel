<!-- resources/views/auth/register.blade.php -->
@extends('layouts.app')

@section('title', 'Регистрация - TravelDream')

@section('content')
<div class="min-h-screen bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md mx-auto">
        <div class="text-center">
            <i class="fas fa-plane-departure text-4xl text-blue-600 mb-4"></i>
            <h2 class="text-3xl font-bold text-gray-900 mb-2">Создать аккаунт</h2>
            <p class="text-gray-600">Присоединяйтесь к TravelDream</p>
        </div>

        <div class="mt-8 bg-white py-8 px-6 shadow rounded-lg">
            <form method="POST" action="{{ route('register') }}">
                @csrf

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="first_name" class="block text-sm font-medium text-gray-700">Имя</label>
                        <input type="text" id="first_name" name="first_name" value="{{ old('first_name') }}" 
                               class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('first_name') border-red-500 @enderror">
                        @error('first_name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="last_name" class="block text-sm font-medium text-gray-700">Фамилия</label>
                        <input type="text" id="last_name" name="last_name" value="{{ old('last_name') }}" 
                               class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('last_name') border-red-500 @enderror">
                        @error('last_name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mt-4">
                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" 
                           class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('email') border-red-500 @enderror">
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mt-4">
                    <label for="phone" class="block text-sm font-medium text-gray-700">Телефон</label>
                    <input type="text" id="phone" name="phone" value="{{ old('phone') }}" 
                           class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('phone') border-red-500 @enderror">
                    @error('phone')
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

                <div class="mt-4">
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Подтверждение пароля</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" 
                           class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                </div>

                <div class="mt-6">
                    <button type="submit" class="w-full bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition">
                        <i class="fas fa-user-plus mr-2"></i>Зарегистрироваться
                    </button>
                </div>
            </form>

            <div class="mt-6 text-center">
                <p class="text-gray-600">
                    Уже есть аккаунт?
                    <a href="{{ route('login') }}" class="text-blue-600 hover:text-blue-500 font-medium">
                        Войдите здесь
                    </a>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection