<!-- resources/views/hotels/index.blade.php -->
@extends('layouts.app')

@section('title', 'Отели - TravelDream')

@section('content')
<div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
    <!-- Заголовок и фильтры -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-4">Наши отели</h1>
        
        <!-- Фильтры -->
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <form method="GET" action="{{ route('hotels.index') }}" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">


                <!-- Город -->
                <div>
                    <label for="city" class="block text-sm font-medium text-gray-700 mb-1">Город</label>
                    <select id="city" name="city" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Все города</option>
                        @foreach($cities as $city)
                            <option value="{{ $city }}" {{ request('city') == $city ? 'selected' : '' }}>{{ $city }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Звезды -->
                <div>
                    <label for="stars" class="block text-sm font-medium text-gray-700 mb-1">Количество звезд</label>
                    <select id="stars" name="stars" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Любые</option>
                        <option value="5" {{ request('stars') == '5' ? 'selected' : '' }}>5 звезд</option>
                        <option value="4" {{ request('stars') == '4' ? 'selected' : '' }}>4 звезды</option>
                        <option value="3" {{ request('stars') == '3' ? 'selected' : '' }}>3 звезды</option>
                        <option value="3" {{ request('stars') == '2' ? 'selected' : '' }}>2 звезды</option>
                        <option value="3" {{ request('stars') == '1' ? 'selected' : '' }}>1 звезда</option>
                    </select>
                </div>

                <!-- Кнопки -->
                <div class="flex items-end gap-3">
                    <button type="submit" class="flex-1 bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-all duration-300 font-medium shadow-md hover:shadow-lg">
                        Найти отели
                    </button>
                    <a href="{{ route('hotels.index') }}" class="flex-1 bg-gray-200 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-300 transition-all duration-300 font-medium text-center">
                        <i class="fas fa-undo-alt mr-2"></i>Сбросить
                    </a>
                </div>
            </form>
        </div>

        <!-- Сортировка и информация -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <p class="text-gray-600">
                Найдено <span class="font-semibold text-blue-600">{{ $hotels->total() }}</span> отелей
            </p>
            
            <div class="flex items-center space-x-4">
                <span class="text-sm text-gray-600">Сортировка:</span>
                <select id="sortSelect" class="border border-gray-300 rounded-md px-3 py-1.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="star_rating_desc" {{ request('sort') == 'star_rating' && request('order') == 'desc' ? 'selected' : '' }}>Звезды (по убыванию)</option>
                    <option value="star_rating_asc" {{ request('sort') == 'star_rating' && request('order') == 'asc' ? 'selected' : '' }}>Звезды (по возрастанию)</option>
                    <option value="name_asc" {{ request('sort') == 'name' && request('order') == 'asc' ? 'selected' : '' }}>Название (А-Я)</option>
                    <option value="name_desc" {{ request('sort') == 'name' && request('order') == 'desc' ? 'selected' : '' }}>Название (Я-А)</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Список отелей -->
    @if($hotels->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
            @foreach($hotels as $hotel)
            <div class="group bg-white rounded-xl shadow-lg overflow-hidden border border-gray-200 hover:shadow-2xl transition-all duration-300 hover:-translate-y-1">
                <div class="relative overflow-hidden h-52">
                    <img src="{{ $hotel->image_url }}" alt="{{ $hotel->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    <div class="absolute top-4 right-4 bg-gradient-to-r from-blue-600 to-blue-700 text-white px-3 py-1 rounded-full text-sm font-semibold shadow-md">
                        {{ $hotel->tours_count }} {{ $hotel->tours_count == 1 ? 'тур' : ($hotel->tours_count < 5 ? 'тура' : 'туров') }}
                    </div>
                    <div class="absolute top-4 left-4 bg-gradient-to-r from-yellow-500 to-yellow-600 text-white px-2 py-1 rounded-lg text-sm font-semibold shadow-md">
                        @for($i = 0; $i < $hotel->star_rating; $i++)
                            ★
                        @endfor
                    </div>
                </div>
                
                <div class="p-5">
                    <h3 class="font-bold text-gray-800 mb-2 text-lg line-clamp-1">{{ $hotel->name }}</h3>
                    
                    <div class="flex items-center mb-2">
                        <i class="fas fa-map-marker-alt text-red-500 mr-2 text-sm"></i>
                        <span class="text-gray-600 text-sm">{{ $hotel->city }}, {{ $hotel->country }}</span>
                    </div>

                    <div class="flex items-center mb-3 text-sm text-gray-600">
                        <i class="fas fa-phone text-green-500 mr-2"></i>
                        <span>{{ $hotel->contact_phone }}</span>
                    </div>

                    <p class="text-gray-500 text-sm mb-4 line-clamp-2">{{ Str::limit($hotel->address, 60) }}</p>
                    
                    <div class="flex justify-between items-center">
                        <a href="{{ route('hotels.show', $hotel->hotel_id) }}" class="bg-blue-600 text-white px-5 py-2 rounded-lg hover:bg-blue-700 transition-all duration-300 font-semibold text-sm shadow-md hover:shadow-lg">
                            <i class="fas fa-eye mr-2"></i>Подробнее
                        </a>
                        <div class="flex items-center">
                            <span class="text-yellow-500 mr-1">★</span>
                            <span class="text-sm font-medium text-gray-700">{{ $hotel->star_rating }}</span>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Пагинация -->
        <div class="mt-8">
            {{ $hotels->links() }}
        </div>
    @else
        <div class="text-center py-16 bg-gray-50 rounded-2xl">
            <i class="fas fa-hotel text-5xl text-gray-300 mb-4"></i>
            <h3 class="text-xl font-semibold text-gray-600 mb-2">Отели не найдены</h3>
            <p class="text-gray-500 mb-6">Попробуйте изменить параметры поиска</p>
            <a href="{{ route('hotels.index') }}" class="inline-block bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition-all duration-300 shadow-md">
                <i class="fas fa-undo-alt mr-2"></i>Сбросить фильтры
            </a>
        </div>
    @endif
</div>

<script>
    // Сортировка
    const sortSelect = document.getElementById('sortSelect');
    if (sortSelect) {
        sortSelect.addEventListener('change', function() {
            const [sort, order] = this.value.split('_');
            const url = new URL(window.location.href);
            url.searchParams.set('sort', sort);
            url.searchParams.set('order', order);
            window.location.href = url.toString();
        });
    }

    // Динамическое обновление городов при выборе страны
    const countrySelect = document.getElementById('country');
    const citySelect = document.getElementById('city');
    
    if (countrySelect && citySelect) {
        countrySelect.addEventListener('change', function() {
            citySelect.value = '';
        });
    }
</script>

<style>
    .line-clamp-1 {
        display: -webkit-box;
        -webkit-line-clamp: 1;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
</style>
@endsection