{{-- resources/views/admin/hotels/edit.blade.php --}}
@extends('layouts.admin')

@section('title', 'Редактирование отеля')

@section('content')
<div class="bg-white rounded-lg shadow">
    <div class="px-6 py-4 border-b border-gray-200">
        <div class="flex justify-between items-center">
            <h1 class="text-2xl font-bold">Редактирование отеля: {{ $hotel->name }}</h1>
            <a href="{{ route('admin.hotels') }}" class="bg-gray-300 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-400 transition">Назад</a>
        </div>
    </div>
    
    <form method="POST" action="{{ route('admin.hotels.update', $hotel->hotel_id) }}" class="p-6">
        @csrf
        @method('PUT')
        
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Левая колонка -->
            <div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Название отеля *</label>
                    <input type="text" name="name" value="{{ old('name', $hotel->name) }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 @error('name') border-red-500 @enderror">
                    @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Страна *</label>
                    <input type="text" name="country" value="{{ old('country', $hotel->country) }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 @error('country') border-red-500 @enderror">
                    @error('country') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Город *</label>
                    <input type="text" name="city" value="{{ old('city', $hotel->city) }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 @error('city') border-red-500 @enderror">
                    @error('city') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Адрес *</label>
                    <input type="text" name="address" value="{{ old('address', $hotel->address) }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 @error('address') border-red-500 @enderror">
                    @error('address') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                
                <div class="grid grid-cols-2 gap-4">
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Количество звезд *</label>
                        <select name="star_rating" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                            <option value="1" {{ old('star_rating', $hotel->star_rating) == 1 ? 'selected' : '' }}>1 звезда</option>
                            <option value="2" {{ old('star_rating', $hotel->star_rating) == 2 ? 'selected' : '' }}>2 звезды</option>
                            <option value="3" {{ old('star_rating', $hotel->star_rating) == 3 ? 'selected' : '' }}>3 звезды</option>
                            <option value="4" {{ old('star_rating', $hotel->star_rating) == 4 ? 'selected' : '' }}>4 звезды</option>
                            <option value="5" {{ old('star_rating', $hotel->star_rating) == 5 ? 'selected' : '' }}>5 звезд</option>
                        </select>
                        @error('star_rating') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Количество номеров *</label>
                        <input type="number" name="room_count" value="{{ old('room_count', $hotel->room_count) }}" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                        @error('room_count') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>
            
            <!-- Правая колонка -->
            <div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Контактный телефон *</label>
                    <input type="text" name="contact_phone" value="{{ old('contact_phone', $hotel->contact_phone) }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 @error('contact_phone') border-red-500 @enderror">
                    @error('contact_phone') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Email *</label>
                    <input type="email" name="email" value="{{ old('email', $hotel->email) }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 @error('email') border-red-500 @enderror">
                    @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Веб-сайт</label>
                    <input type="url" name="website" value="{{ old('website', $hotel->website) }}" placeholder="https://..." class="w-full border border-gray-300 rounded-lg px-3 py-2">
                </div>
                
                <div class="grid grid-cols-2 gap-4">
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Время заезда *</label>
                        <input type="text" name="check_in_time" value="{{ old('check_in_time', $hotel->check_in_time) }}" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                        @error('check_in_time') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Время выезда *</label>
                        <input type="text" name="check_out_time" value="{{ old('check_out_time', $hotel->check_out_time) }}" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                        @error('check_out_time') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">URL изображения *</label>
                    <input type="url" name="image_url" value="{{ old('image_url', $hotel->image_url) }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 @error('image_url') border-red-500 @enderror">
                    <div class="mt-2">
                        <img src="{{ $hotel->image_url }}" alt="Preview" class="w-32 h-32 object-cover rounded">
                    </div>
                    @error('image_url') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>
        
        <!-- Удобства -->
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-2">Удобства и услуги</label>
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3 p-4 bg-gray-50 rounded-lg">
                @php
                    $amenitiesList = [
                        'Бесплатный Wi-Fi', 'Парковка', 'Ресторан', 'Бар', 'Бассейн', 'Спа-центр',
                        'Фитнес-центр', 'Конференц-зал', 'Трансфер', 'Кондиционер', 'Телевизор',
                        'Мини-бар', 'Сейф', 'Круглосуточная стойка регистрации', 'Обслуживание в номерах',
                        'Прачечная', 'Детская кроватка', 'Номера для некурящих', 'Доступ для инвалидов'
                    ];
                    $currentAmenities = is_array($amenities) ? $amenities : json_decode($hotel->amenities, true) ?? [];
                @endphp
                @foreach($amenitiesList as $amenity)
                    <label class="flex items-center space-x-2">
                        <input type="checkbox" name="amenities[]" value="{{ $amenity }}" 
                            {{ in_array($amenity, $currentAmenities) ? 'checked' : '' }} 
                            class="rounded border-gray-300">
                        <span class="text-sm text-gray-700">{{ $amenity }}</span>
                    </label>
                @endforeach
            </div>
        </div>
        
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-2">Описание отеля *</label>
            <textarea name="description" rows="6" class="w-full border border-gray-300 rounded-lg px-3 py-2 @error('description') border-red-500 @enderror">{{ old('description', $hotel->description) }}</textarea>
            @error('description') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>
        
        <div class="mt-6 flex justify-end space-x-3 pt-4 border-t">
            <a href="{{ route('admin.hotels') }}" class="px-6 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition">Отмена</a>
            <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                <i class="fas fa-save mr-2"></i>Сохранить изменения
            </button>
        </div>
    </form>
</div>
@endsection