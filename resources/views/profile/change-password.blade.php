<!-- resources/views/profile/change-password.blade.php -->
@extends('layouts.app')

@section('title', 'Смена пароля - TravelDream')

@section('content')
<div class="max-w-md mx-auto py-12 px-4 sm:px-6 lg:px-8">
    <div class="bg-white shadow rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200">
            <h1 class="text-2xl font-bold text-gray-900">Смена пароля</h1>
        </div>

        <div class="px-6 py-4">
            <form method="POST" action="{{ route('profile.change-password') }}">
                @csrf

                <div class="mt-4">
                    <label for="current_password" class="block text-sm font-medium text-gray-700">Текущий пароль *</label>
                    <input type="password" id="current_password" name="current_password" 
                           class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('current_password') border-red-500 @enderror">
                    @error('current_password')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mt-4">
                    <label for="password" class="block text-sm font-medium text-gray-700">Новый пароль *</label>
                    <input type="password" id="password" name="password" 
                           class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('password') border-red-500 @enderror">
                    @error('password')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mt-4">
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Подтверждение пароля *</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" 
                           class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                </div>

                <div class="mt-6 flex space-x-4">
                    <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition">
                        <i class="fas fa-lock mr-2"></i>Сменить пароль
                    </button>
                    <a href="{{ route('profile.show') }}" class="bg-gray-300 text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-400 transition">
                        <i class="fas fa-times mr-2"></i>Отмена
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection