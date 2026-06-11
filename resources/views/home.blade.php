<!-- resources/views/home.blade.php -->
@extends('layouts.app')

@section('title', 'TravelDream - Лучшие туры по всей России')

@section('content')
    <!-- Hero Section -->
    <section class="hero-bg text-white py-24">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <h1 class="text-6xl font-bold mb-6 animate-fade-in">Путешествуйте по России <br>с TravelDream</h1>
            <p class="text-xl mb-10 max-w-2xl mx-auto opacity-90">Лучшие туры по России с комфортным проживанием и незабываемыми впечатлениями</p>
            <div class="flex flex-col sm:flex-row justify-center gap-5">
                <a href="{{ route('tours.index') }}" class="bg-white text-blue-600 px-8 py-3 rounded-xl font-semibold hover:bg-gray-100 hover:scale-105 transition-all duration-300 inline-flex items-center justify-center shadow-lg">
                    <i class="fas fa-search mr-2"></i>Найти тур
                </a>
            </div>
        </div>
    </section>

    <!-- Tours -->
    <section id="tours" class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-4xl font-bold text-gray-800 mb-3">Популярные туры</h2>
                <div class="w-20 h-1 bg-blue-600 mx-auto rounded-full"></div>
                <p class="text-gray-600 mt-4">Откройте для себя наши самые востребованные направления</p>
            </div>

            @if($featuredTours->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($featuredTours as $tour)
                <div class="group bg-white rounded-2xl shadow-md hover:shadow-2xl transition-all duration-300 overflow-hidden hover:-translate-y-2">
                    <div class="relative overflow-hidden h-56">
                        <img src="{{ $tour->image_url }}" alt="{{ $tour->title }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                        <div class="absolute top-4 right-4 bg-gradient-to-r from-blue-600 to-blue-700 text-white px-3 py-1.5 rounded-full text-sm font-semibold shadow-lg">
                            {{ $tour->formatted_price }} ₽
                        </div>
                        @if($tour->available_spots < 5)
                        <div class="absolute top-4 left-4 bg-red-500 text-white px-2 py-1 rounded-lg text-xs font-semibold animate-pulse">
                            ⚡ Осталось {{ $tour->available_spots }} мест
                        </div>
                        @endif
                    </div>
                    
                    <div class="p-5">
                        <div class="flex items-center mb-2 text-sm">
                            <i class="fas fa-map-marker-alt text-red-500 mr-1"></i>
                            <span class="text-gray-500">{{ $tour->destination_city }}, {{ $tour->destination_country }}</span>
                        </div>
                        
                        <h3 class="text-lg font-bold text-gray-800 mb-2 line-clamp-1">{{ $tour->title }}</h3>
                        
                        <div class="flex items-center justify-between mb-3 text-sm">
                            <div class="flex items-center text-gray-500">
                                <i class="fas fa-calendar-alt text-blue-500 mr-1"></i>
                                <span>{{ $tour->duration }} дней</span>
                            </div>
                            <div class="flex items-center">
                                <div class="flex text-yellow-400 mr-1">
                                    @for($i = 0; $i < floor($tour->average_rating); $i++) <i class="fas fa-star"></i> @endfor
                                    @if($tour->average_rating - floor($tour->average_rating) >= 0.5) <i class="fas fa-star-half-alt"></i> @endif
                                </div>
                                <span class="text-gray-500 text-sm">({{ $tour->reviews_count }})</span>
                            </div>
                        </div>

                        <div class="flex items-center mb-4 text-xs text-gray-500 bg-gray-100 py-1.5 px-3 rounded-full inline-block">
                            <i class="fas fa-hotel text-green-500 mr-1"></i>
                            <span>{{ $tour->hotel->name }} • {{ $tour->hotel->star_rating }}★</span>
                        </div>
                        
                        <a href="{{ route('tours.show', $tour->tour_id) }}" class="block w-full bg-blue-600 text-white text-center py-2.5 rounded-xl hover:bg-blue-700 transition-all duration-300 font-semibold text-sm group-hover:shadow-md">
                            <i class="fas fa-eye mr-2"></i>Подробнее
                        </a>
                    </div>
                </div>
                @endforeach
            </div>

            <div class="text-center mt-12">
                <a href="{{ route('tours.index') }}" class="bg-gray-800 text-white px-8 py-3 rounded-xl font-semibold hover:bg-gray-900 hover:scale-105 transition-all duration-300 inline-flex items-center shadow-md">
                    <i class="fas fa-list mr-2"></i>Смотреть все туры
                    <i class="fas fa-arrow-right ml-2"></i>
                </a>
            </div>
            @else
            <div class="text-center py-16 bg-white rounded-2xl">
                <i class="fas fa-plane-slash text-5xl text-gray-300 mb-4"></i>
                <p class="text-gray-500 text-lg">На данный момент нет доступных туров</p>
            </div>
            @endif
        </div>
    </section>

    <!-- Statistics -->
    <section class="py-16 bg-gradient-to-r from-blue-600 to-blue-800 text-white">
        <div class="max-w-7xl mx-auto px-4">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
                <div class="transform hover:scale-105 transition-all duration-300">
                    <div class="text-4xl md:text-5xl font-bold mb-2">{{ $stats['total_tours'] }}+</div>
                    <div class="text-blue-100 text-sm uppercase tracking-wide">Доступных туров</div>
                </div>
                <div class="transform hover:scale-105 transition-all duration-300">
                    <div class="text-4xl md:text-5xl font-bold mb-2">{{ $stats['total_hotels'] }}+</div>
                    <div class="text-blue-100 text-sm uppercase tracking-wide">Партнерских отелей</div>
                </div>
                <div class="transform hover:scale-105 transition-all duration-300">
                    <div class="text-4xl md:text-5xl font-bold mb-2">{{ $stats['available_tours'] }}+</div>
                    <div class="text-blue-100 text-sm uppercase tracking-wide">Свободных мест</div>
                </div>
                <div class="transform hover:scale-105 transition-all duration-300">
                    <div class="text-4xl md:text-5xl font-bold mb-2">24/7</div>
                    <div class="text-blue-100 text-sm uppercase tracking-wide">Поддержка</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Reviews -->
    <section id="reviews" class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-4xl font-bold text-gray-800 mb-3">Отзывы путешественников</h2>
                <div class="w-20 h-1 bg-blue-600 mx-auto rounded-full"></div>
                <p class="text-gray-600 mt-4">Что говорят наши клиенты</p>
            </div>

            @if($reviews->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($reviews as $review)
                <div class="bg-gradient-to-br from-gray-50 to-white rounded-2xl p-6 border border-gray-100 shadow-md hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex text-yellow-400">
                            @for($i = 0; $i < $review->rating; $i++)
                                <i class="fas fa-star text-sm"></i>
                            @endfor
                            @for($i = $review->rating; $i < 5; $i++)
                                <i class="far fa-star text-sm"></i>
                            @endfor
                        </div>
                        <span class="text-gray-400 text-xs">{{ $review->created_at->format('d.m.Y') }}</span>
                    </div>
                    <p class="text-gray-700 mb-5 leading-relaxed line-clamp-4">"{{ $review->comment }}"</p>
                    <div class="flex items-center pt-3 border-t border-gray-100">
                        <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-white font-bold text-sm shadow-md mr-3">
                            {{ substr($review->client->first_name, 0, 1) }}{{ substr($review->client->last_name, 0, 1) }}
                        </div>
                        <div>
                            <div class="font-semibold text-gray-800 text-sm">{{ $review->client->first_name }} {{ $review->client->last_name }}</div>
                            <div class="text-xs text-gray-500">{{ Str::limit($review->tour->title, 30) }}</div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            <div class="text-center mt-10">
                <a href="{{ route('reviews.index') }}" class="text-blue-600 hover:text-blue-700 font-semibold inline-flex items-center gap-1">
                    Все отзывы <i class="fas fa-arrow-right"></i>
                </a>
            </div>
            @else
            <div class="text-center py-12 bg-gray-50 rounded-2xl">
                <i class="fas fa-comment-slash text-4xl text-gray-300 mb-3"></i>
                <p class="text-gray-500">Пока нет отзывов. Будьте первым!</p>
            </div>
            @endif
        </div>
    </section>

    <section class="py-16 bg-gradient-to-r from-blue-600 to-purple-700 text-white">
        <div class="max-w-4xl mx-auto px-4 text-center">
            <h2 class="text-4xl md:text-5xl font-bold mb-4">Готовы к путешествию?</h2>
            <p class="text-xl mb-6 opacity-95">Присоединяйтесь к тысячам довольных путешественников</p>
            <div class="flex flex-col sm:flex-row justify-center gap-5">
                <a href="{{ route('tours.index') }}" class="bg-white text-blue-600 px-8 py-3 rounded-xl font-semibold hover:bg-gray-100 hover:scale-105 transition-all duration-300 inline-flex items-center justify-center shadow-lg">
                    <i class="fas fa-search mr-2"></i>Найти тур
                </a>
                <a href="tel:+79991234567" class="border-2 border-white text-white px-8 py-3 rounded-xl font-semibold hover:bg-white hover:text-blue-600 hover:scale-105 transition-all duration-300 inline-flex items-center justify-center">
                    <i class="fas fa-phone-alt mr-2"></i>Позвонить нам
                </a>
            </div>
        </div>
    </section>
@endsection

<style>
    @keyframes fade-in {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .animate-fade-in {
        animation: fade-in 0.8s ease-out;
    }
    
    .line-clamp-1 {
        display: -webkit-box;
        -webkit-line-clamp: 1;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    
    .line-clamp-4 {
        display: -webkit-box;
        -webkit-line-clamp: 4;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
</style>