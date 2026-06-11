<!-- resources/views/profile/edit.blade.php -->
@extends('layouts.app')

@section('title', 'Редактирование профиля - TravelDream')

@section('content')
<div class="max-w-2xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
    <div class="bg-white shadow rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200">
            <h1 class="text-2xl font-bold text-gray-900">Редактирование профиля</h1>
        </div>

        <div class="px-6 py-4">
            <form method="POST" action="{{ route('profile.update') }}">
                @csrf

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="first_name" class="block text-sm font-medium text-gray-700">Имя *</label>
                        <input type="text" id="first_name" name="first_name" value="{{ old('first_name', $client->first_name) }}" 
                               class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('first_name') border-red-500 @enderror">
                        @error('first_name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="last_name" class="block text-sm font-medium text-gray-700">Фамилия *</label>
                        <input type="text" id="last_name" name="last_name" value="{{ old('last_name', $client->last_name) }}" 
                               class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('last_name') border-red-500 @enderror">
                        @error('last_name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mt-4">
                    <label for="email" class="block text-sm font-medium text-gray-700">Email *</label>
                    <input type="email" id="email" name="email" value="{{ old('email', $client->email) }}" 
                           class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('email') border-red-500 @enderror">
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mt-4">
                    <label for="phone" class="block text-sm font-medium text-gray-700">Телефон *</label>
                    <input type="text" id="phone" name="phone" value="{{ old('phone', $client->phone) }}" 
                           class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('phone') border-red-500 @enderror">
                    @error('phone')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mt-6 flex space-x-4">
                    <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition">
                        <i class="fas fa-save mr-2"></i>Сохранить изменения
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