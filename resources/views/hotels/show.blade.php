<!-- resources/views/hotels/show.blade.php -->
@extends('layouts.app')

@section('title', $hotel->name . ' - TravelDream')

@section('content')
<div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
    <!-- Хлебные крошки -->
    <nav class="flex mb-8" aria-label="Breadcrumb">
        <ol class="flex items-center space-x-2 text-sm">
            <li>
                <a href="{{ route('home') }}" class="text-gray-500 hover:text-gray-700">Главная</a>
            </li>
            <li>
                <i class="fas fa-chevron-right text-gray-400 text-xs"></i>
            </li>
            <li>
                <a href="{{ route('hotels.index') }}" class="text-gray-500 hover:text-gray-700">Отели</a>
            </li>
            <li>
                <i class="fas fa-chevron-right text-gray-400 text-xs"></i>
            </li>
            <li>
                <span class="text-gray-900 font-medium">{{ $hotel->name }}</span>
            </li>
        </ol>
    </nav>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Основная информация -->
        <div class="lg:col-span-2">
            <!-- Галерея и основная информация -->
            <div class="bg-white rounded-xl shadow-lg overflow-hidden mb-6">
                <img src="{{ $hotel->image_url }}" alt="{{ $hotel->name }}" class="w-full h-96 object-cover">
                
                <div class="p-6">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-4">
                        <div>
                            <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $hotel->name }}</h1>
                            <div class="flex items-center text-yellow-500 text-lg mb-2">
                                @for($i = 0; $i < $hotel->star_rating; $i++)
                                    ★
                                @endfor
                                <span class="ml-2 text-gray-600 text-sm">({{ $hotel->star_rating }} звезд)</span>
                            </div>
                        </div>
                        <div class="bg-blue-50 rounded-lg p-4 text-center">
                            <div class="text-2xl font-bold text-blue-600">{{ $hotelStats['total_tours'] }}</div>
                            <div class="text-blue-600 font-medium">доступных туров</div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div class="space-y-3">
                            <div class="flex items-start">
                                <i class="fas fa-map-marker-alt text-red-500 mt-1 mr-3 w-5"></i>
                                <div>
                                    <div class="font-semibold">Адрес</div>
                                    <div class="text-gray-600">{{ $hotel->full_address }}</div>
                                </div>
                            </div>
                            
                            <div class="flex items-start">
                                <i class="fas fa-phone text-green-500 mt-1 mr-3 w-5"></i>
                                <div>
                                    <div class="font-semibold">Телефон</div>
                                    <div class="text-gray-600">{{ $hotel->contact_phone }}</div>
                                </div>
                            </div>

                            <div class="flex items-start">
                                <i class="fas fa-envelope text-blue-500 mt-1 mr-3 w-5"></i>
                                <div>
                                    <div class="font-semibold">Email</div>
                                    <div class="text-gray-600">{{ $hotel->email }}</div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="space-y-3">
                            <div class="flex items-start">
                                <i class="fas fa-star text-yellow-500 mt-1 mr-3 w-5"></i>
                                <div>
                                    <div class="font-semibold">Категория</div>
                                    <div class="text-gray-600">{{ $hotel->star_rating }}-звездочный отель</div>
                                </div>
                            </div>
                            
                            <div class="flex items-start">
                                <i class="fas fa-bed text-purple-500 mt-1 mr-3 w-5"></i>
                                <div>
                                    <div class="font-semibold">Количество номеров</div>
                                    <div class="text-gray-600">{{ $hotel->room_count }} номеров</div>
                                </div>
                            </div>

                            <div class="flex items-start">
                                <i class="fas fa-clock text-orange-500 mt-1 mr-3 w-5"></i>
                                <div>
                                    <div class="font-semibold">Заезд/Выезд</div>
                                    <div class="text-gray-600">{{ $hotel->check_in_time }} / {{ $hotel->check_out_time }}</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Описание -->
                    <div class="mb-6">
                        <h2 class="text-xl font-semibold mb-3">Об отеле</h2>
                        <p class="text-gray-700 leading-relaxed">{{ $hotel->description }}</p>
                    </div>

                    <!-- Удобства -->
                    <div class="mb-6">
                        <h2 class="text-xl font-semibold mb-3">Удобства и услуги</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach($hotel->amenities_list as $amenity)
                            <div class="flex items-center">
                                <i class="fas fa-check text-green-500 mr-3"></i>
                                <span>{{ $amenity }}</span>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- Доступные туры -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h2 class="text-2xl font-semibold mb-6">Туры с проживанием в этом отеле</h2>
                
                @if($hotel->tours->count() > 0)
                    <div class="space-y-6">
                        @foreach($hotel->tours as $tour)
                        <div class="border border-gray-200 rounded-lg p-4 hover:border-blue-300 transition">
                            <div class="flex flex-col md:flex-row md:items-center gap-4">
                                <img src="{{ $tour->image_url }}" alt="{{ $tour->title }}" class="w-full md:w-32 h-32 object-cover rounded-lg">
                                
                                <div class="flex-1">
                                    <h3 class="font-semibold text-lg text-gray-900 mb-2">{{ $tour->title }}</h3>
                                    
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-3">
                                        <div class="space-y-1 text-sm">
                                            <div class="flex items-center">
                                                <i class="fas fa-calendar text-blue-500 mr-2"></i>
                                                <span>{{ $tour->start_date->format('d.m.Y') }} - {{ $tour->end_date->format('d.m.Y') }}</span>
                                            </div>
                                            <div class="flex items-center">
                                                <i class="fas fa-clock text-green-500 mr-2"></i>
                                                <span>{{ $tour->duration }} дней</span>
                                            </div>
                                        </div>
                                        <div class="space-y-1 text-sm">
                                            <div class="flex items-center">
                                                <i class="fas fa-users text-purple-500 mr-2"></i>
                                                <span>Доступно мест: {{ $tour->available_spots }}</span>
                                            </div>
                                            <div class="flex items-center">
                                                <i class="fas fa-star text-yellow-400 mr-2"></i>
                                                <span>Рейтинг: {{ number_format($tour->average_rating, 1) }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="text-center">
                                    <div class="text-2xl font-bold text-blue-600 mb-2">{{ number_format($tour->price, 0, '', ' ') }} ₽</div>
                                    <a href="{{ route('tours.show', $tour->tour_id) }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition text-sm font-semibold inline-block">
                                        Подробнее
                                    </a>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <i class="fas fa-suitcase text-4xl text-gray-400 mb-4"></i>
                        <p class="text-gray-600">В настоящее время нет доступных туров с проживанием в этом отеле.</p>
                        <p class="text-gray-500 text-sm mt-2">Следите за обновлениями!</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Боковая панель -->
        <div class="lg:col-span-1">
            <!-- Статистика -->
            <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
                <h3 class="text-lg font-semibold mb-4">Статистика отеля</h3>
                <div class="space-y-4">
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Всего туров:</span>
                        <span class="font-semibold">{{ $hotelStats['total_tours'] }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Количество номеров:</span>
                        <span class="font-semibold">{{ $hotel->room_count }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Средний рейтинг:</span>
                        <span class="font-semibold">
                            @if($hotelStats['average_rating'] > 0)
                                {{ number_format($hotelStats['average_rating'], 1) }}/5
                            @else
                                -
                            @endif
                        </span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Диапазон цен:</span>
                        <span class="font-semibold">
                            @if($hotelStats['min_price'] > 0)
                                {{ number_format($hotelStats['min_price'], 0, '', ' ') }} - {{ number_format($hotelStats['max_price'], 0, '', ' ') }} ₽
                            @else
                                -
                            @endif
                        </span>
                    </div>
                </div>
            </div>

            <!-- Контактная информация -->
            <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
                <h3 class="text-lg font-semibold mb-4">Контактная информация</h3>
                <div class="space-y-3 text-sm">
                    <div class="flex items-start">
                        <i class="fas fa-phone text-green-500 mt-1 mr-3"></i>
                        <div>
                            <div class="font-medium">Телефон</div>
                            <div class="text-gray-600">{{ $hotel->contact_phone }}</div>
                        </div>
                    </div>
                    <div class="flex items-start">
                        <i class="fas fa-envelope text-blue-500 mt-1 mr-3"></i>
                        <div>
                            <div class="font-medium">Email</div>
                            <div class="text-gray-600">{{ $hotel->email }}</div>
                        </div>
                    </div>
                    <div class="flex items-start">
                        <i class="fas fa-globe text-purple-500 mt-1 mr-3"></i>
                        <div>
                            <div class="font-medium">Веб-сайт</div>
                            <a href="{{ $hotel->website }}" target="_blank" class="text-blue-600 hover:text-blue-500">{{ $hotel->website }}</a>
                        </div>
                    </div>
                    <div class="flex items-start">
                        <i class="fas fa-map-marker-alt text-red-500 mt-1 mr-3"></i>
                        <div>
                            <div class="font-medium">Адрес</div>
                            <div class="text-gray-600">{{ $hotel->address }}</div>
                            <div class="text-gray-500">{{ $hotel->city }}, {{ $hotel->country }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Похожие отели -->
            @if($relatedHotels->count() > 0)
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h3 class="text-lg font-semibold mb-4">Похожие отели</h3>
                <div class="space-y-4">
                    @foreach($relatedHotels as $relatedHotel)
                    <a href="{{ route('hotels.show', $relatedHotel->hotel_id) }}" class="flex items-center space-x-3 p-3 rounded-lg hover:bg-gray-50 transition">
                        <img src="{{ $relatedHotel->image_url }}" alt="{{ $relatedHotel->name }}" class="w-16 h-16 object-cover rounded">
                        <div class="flex-1">
                            <h4 class="font-semibold text-sm line-clamp-2">{{ $relatedHotel->name }}</h4>
                            <div class="flex items-center justify-between mt-1">
                                <span class="text-yellow-500 text-xs">
                                    @for($i = 0; $i < $relatedHotel->star_rating; $i++)
                                        ★
                                    @endfor
                                </span>
                                <span class="text-blue-600 text-xs font-semibold">{{ $relatedHotel->tours_count }} тур.</span>
                            </div>
                        </div>
                    </a>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<style>
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
</style>
@endsection