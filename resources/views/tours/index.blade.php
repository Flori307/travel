<!-- resources/views/tours/index.blade.php -->
@extends('layouts.app')

@section('title', 'Все туры - TravelDream')

@section('content')
<div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
    <!-- Заголовок и фильтры -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-4">Все туры</h1>
        
        <!-- Фильтры -->
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <form method="GET" action="{{ route('tours.index') }}" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">


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

                <!-- Цена от -->
                <div>
                    <label for="price_min" class="block text-sm font-medium text-gray-700 mb-1">Цена от</label>
                    <input type="number" id="price_min" name="price_min" value="{{ request('price_min') }}" 
                           placeholder="0" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                </div>

                <!-- Цена до -->
                <div>
                    <label for="price_max" class="block text-sm font-medium text-gray-700 mb-1">Цена до</label>
                    <input type="number" id="price_max" name="price_max" value="{{ request('price_max') }}" 
                           placeholder="200000" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                </div>

                <!-- Звезды отеля -->
                <div>
                    <label for="hotel_stars" class="block text-sm font-medium text-gray-700 mb-1">Звезды отеля</label>
                    <select id="hotel_stars" name="hotel_stars" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Любые</option>
                        <option value="5" {{ request('hotel_stars') == '5' ? 'selected' : '' }}>5 звезд</option>
                        <option value="4" {{ request('hotel_stars') == '4' ? 'selected' : '' }}>4 звезды</option>
                        <option value="3" {{ request('hotel_stars') == '3' ? 'selected' : '' }}>3 звезды</option>
                        <option value="3" {{ request('hotel_stars') == '2' ? 'selected' : '' }}>2 звезды</option>
                        <option value="3" {{ request('hotel_stars') == '1' ? 'selected' : '' }}>1 звезда</option>
                    </select>
                </div>

                <!-- Кнопки -->
                <div class="lg:col-span-5 flex space-x-4 pt-4">
                    <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition flex items-center">
                        Найти туры
                    </button>
                    <a href="{{ route('tours.index') }}" class="bg-gray-300 text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-400 transition flex items-center">
                        <i class="fas fa-times mr-2"></i>Сбросить
                    </a>
                </div>
            </form>
        </div>

        <!-- Сортировка и информация -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <p class="text-gray-600">
                Найдено <span class="font-semibold">{{ $tours->total() }}</span> туров
            </p>
            
            <div class="flex items-center space-x-4">
                <span class="text-sm text-gray-600">Сортировка:</span>
                <select id="sortSelect" class="border border-gray-300 rounded-md px-3 py-1 text-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    <option value="start_date_asc" {{ request('sort') == 'start_date' && request('order') == 'asc' ? 'selected' : '' }}>Дата начала (сначала ближайшие)</option>
                    <option value="price_asc" {{ request('sort') == 'price' && request('order') == 'asc' ? 'selected' : '' }}>Цена (по возрастанию)</option>
                    <option value="price_desc" {{ request('sort') == 'price' && request('order') == 'desc' ? 'selected' : '' }}>Цена (по убыванию)</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Список туров -->
    @if($tours->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 mb-8">
            @foreach($tours as $tour)
            <div class="tour-card bg-white rounded-xl shadow-lg overflow-hidden border border-gray-200 hover:shadow-xl transition-shadow duration-300">
                <div class="relative">
                    <img src="{{ $tour->image_url }}" alt="{{ $tour->title }}" class="w-full h-48 object-cover">
                    <div class="absolute top-4 right-4 bg-blue-600 text-white px-3 py-1 rounded-full text-sm font-semibold">
                        {{ number_format($tour->price, 0, '', ' ') }} ₽
                    </div>
                    @if($tour->available_spots < 5)
                    <div class="absolute top-4 left-4 bg-red-500 text-white px-2 py-1 rounded text-xs font-semibold">
                        Осталось {{ $tour->available_spots }} мест
                    </div>
                    @endif
                </div>
                
                <div class="p-4">
                    <div class="flex items-center mb-2">
                        <i class="fas fa-map-marker-alt text-red-500 mr-2 text-sm"></i>
                        <span class="text-gray-600 text-sm">{{ $tour->destination_city }}, {{ $tour->destination_country }}</span>
                    </div>
                    
                    <h3 class="font-semibold text-gray-800 mb-2 line-clamp-2">{{ $tour->title }}</h3>
                    
                    <div class="flex items-center justify-between mb-3">
                        <div class="flex items-center">
                            <i class="fas fa-calendar text-blue-500 mr-2 text-sm"></i>
                            <span class="text-gray-600 text-sm">{{ $tour->duration }} дней</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-star text-yellow-400 mr-1 text-sm"></i>
                            <span class="text-gray-600 text-sm">{{ number_format($tour->average_rating, 1) }}</span>
                            <span class="text-gray-400 text-xs ml-1">({{ $tour->reviews_count }})</span>
                        </div>
                    </div>

                    <div class="flex items-center mb-4 text-sm text-gray-600">
                        <i class="fas fa-hotel text-green-500 mr-2"></i>
                        <span class="truncate">{{ $tour->hotel->name }}</span>
                        <span class="ml-2 text-yellow-500">
                            @for($i = 0; $i < $tour->hotel->star_rating; $i++)
                                ★
                            @endfor
                        </span>
                    </div>

                    <div class="flex items-center justify-between text-sm text-gray-500 mb-3">
                        <span>{{ $tour->start_date->format('d.m.Y') }} - {{ $tour->end_date->format('d.m.Y') }}</span>
                    </div>
                    
                    <a href="{{ route('tours.show', $tour->tour_id) }}" class="block w-full bg-blue-600 text-white text-center py-2 rounded-lg hover:bg-blue-700 transition font-semibold text-sm">
                        <i class="fas fa-eye mr-2"></i>Подробнее
                    </a>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Пагинация -->
        <div class="mt-8">
            {{ $tours->links() }}
        </div>
    @else
        <div class="text-center py-12">
            <i class="fas fa-search text-4xl text-gray-400 mb-4"></i>
            <h3 class="text-xl font-semibold text-gray-600 mb-2">Туры не найдены</h3>
            <p class="text-gray-500 mb-6">Попробуйте изменить параметры поиска</p>
            <a href="{{ route('tours.index') }}" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition">
                <i class="fas fa-times mr-2"></i>Сбросить фильтры
            </a>
        </div>
    @endif
</div>

<script>
    // Сортировка
    document.getElementById('sortSelect').addEventListener('change', function() {
        const [sort, order] = this.value.split('_');
        const url = new URL(window.location.href);
        url.searchParams.set('sort', sort);
        url.searchParams.set('order', order);
        window.location.href = url.toString();
    });

    // Динамическое обновление городов при выборе страны
    document.getElementById('country').addEventListener('change', function() {
        // Здесь можно добавить AJAX запрос для загрузки городов выбранной страны
        // Пока просто сбрасываем город
        document.getElementById('city').value = '';
    });
</script>

<style>
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
</style>
@endsection