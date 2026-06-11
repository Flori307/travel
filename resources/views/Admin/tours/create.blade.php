{{-- resources/views/admin/tours/create.blade.php --}}
@extends('layouts.admin')

@section('title', 'Создание тура')

@section('content')
<div class="bg-white rounded-lg shadow">
    <div class="px-6 py-4 border-b border-gray-200">
        <div class="flex justify-between items-center">
            <h1 class="text-2xl font-bold">Создание нового тура</h1>
            <a href="{{ route('admin.tours') }}" class="bg-gray-300 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-400 transition">Назад</a>
        </div>
    </div>
    
    <form method="POST" action="{{ route('admin.tours.store') }}" class="p-6">
        @csrf
        
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Левая колонка -->
            <div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Название тура *</label>
                    <input type="text" name="title" value="{{ old('title') }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 @error('title') border-red-500 @enderror">
                    @error('title') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Отель *</label>
                    <select name="hotel_id" class="w-full border border-gray-300 rounded-lg px-3 py-2 @error('hotel_id') border-red-500 @enderror">
                        <option value="">Выберите отель</option>
                        @foreach($hotels as $hotel)
                            <option value="{{ $hotel->hotel_id }}" {{ old('hotel_id') == $hotel->hotel_id ? 'selected' : '' }}>
                                {{ $hotel->name }} ({{ $hotel->city }}, {{ $hotel->star_rating }}★)
                            </option>
                        @endforeach
                    </select>
                    @error('hotel_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Страна *</label>
                    <input type="text" name="destination_country" value="{{ old('destination_country') }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 @error('destination_country') border-red-500 @enderror">
                    @error('destination_country') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Город *</label>
                    <input type="text" name="destination_city" value="{{ old('destination_city') }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 @error('destination_city') border-red-500 @enderror">
                    @error('destination_city') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                
                <div class="grid grid-cols-2 gap-4">
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Дата начала *</label>
                        <input type="date" name="start_date" value="{{ old('start_date') }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 @error('start_date') border-red-500 @enderror">
                        @error('start_date') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Дата окончания *</label>
                        <input type="date" name="end_date" value="{{ old('end_date') }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 @error('end_date') border-red-500 @enderror">
                        @error('end_date') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>
            
            <!-- Правая колонка -->
            <div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Цена (₽) *</label>
                    <input type="number" name="price" value="{{ old('price') }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 @error('price') border-red-500 @enderror">
                    @error('price') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Доступные места *</label>
                    <input type="number" name="available_spots" value="{{ old('available_spots', 20) }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 @error('available_spots') border-red-500 @enderror">
                    @error('available_spots') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">URL изображения *</label>
                    <input type="url" name="image_url" value="{{ old('image_url') }}" placeholder="https://..." class="w-full border border-gray-300 rounded-lg px-3 py-2 @error('image_url') border-red-500 @enderror">
                    @error('image_url') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Описание *</label>
                    <textarea name="description" rows="8" class="w-full border border-gray-300 rounded-lg px-3 py-2 @error('description') border-red-500 @enderror">{{ old('description') }}</textarea>
                    @error('description') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>
        
        <div class="mt-6 flex justify-end space-x-3 pt-4 border-t">
            <a href="{{ route('admin.tours') }}" class="px-6 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition">Отмена</a>
            <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                <i class="fas fa-save mr-2"></i>Создать тур
            </button>
        </div>
    </form>
</div>
@endsection