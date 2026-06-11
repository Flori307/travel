{{-- resources/views/admin/users/edit.blade.php --}}
@extends('layouts.admin')

@section('title', 'Редактирование пользователя')

@section('content')
<div class="bg-white rounded-lg shadow p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Редактирование пользователя: {{ $user->full_name }}</h1>
        <a href="{{ route('admin.users.show', $user->client_id) }}" class="bg-gray-300 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-400">Назад</a>
    </div>
    
    <form method="POST" action="{{ route('admin.users.update', $user->client_id) }}">
        @csrf
        @method('PUT')
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Имя *</label>
                <input type="text" name="first_name" value="{{ old('first_name', $user->first_name) }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 @error('first_name') border-red-500 @enderror">
                @error('first_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Фамилия *</label>
                <input type="text" name="last_name" value="{{ old('last_name', $user->last_name) }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 @error('last_name') border-red-500 @enderror">
                @error('last_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Email *</label>
                <input type="email" name="email" value="{{ old('email', $user->email) }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 @error('email') border-red-500 @enderror">
                @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Телефон *</label>
                <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 @error('phone') border-red-500 @enderror">
                @error('phone') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Роль *</label>
                <select name="role" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                    <option value="user" {{ $user->role == 'user' ? 'selected' : '' }}>Пользователь</option>
                    <option value="moderator" {{ $user->role == 'moderator' ? 'selected' : '' }}>Модератор</option>
                    @if(Auth::user()->role == 'admin')
                    <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Администратор</option>
                    @endif
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Новый пароль (оставьте пустым, если не менять)</label>
                <input type="password" name="password" class="w-full border border-gray-300 rounded-lg px-3 py-2">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Подтверждение пароля</label>
                <input type="password" name="password_confirmation" class="w-full border border-gray-300 rounded-lg px-3 py-2">
            </div>
        </div>
        
        <div class="mt-6 flex justify-end space-x-3">
            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700">Сохранить</button>
        </div>
    </form>
</div>
@endsection