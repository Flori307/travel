<!-- resources/views/reviews/index.blade.php -->
@extends('layouts.app')

@section('title', 'Отзывы путешественников - TravelDream')

@section('content')
<div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
    <!-- Заголовок -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-4">Отзывы путешественников</h1>
        <p class="text-gray-600 max-w-2xl">Читайте реальные отзывы наших клиентов о путешествиях с TravelDream</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        <!-- Боковая панель с фильтрами и статистикой -->
        <div class="lg:col-span-1">
            <!-- Статистика -->
            <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
                <h3 class="text-lg font-semibold mb-4">Общая статистика</h3>
                <div class="space-y-4">
                    <div class="text-center">
                        <div class="text-3xl font-bold text-blue-600 mb-2">{{ number_format($stats['average_rating'], 1) }}</div>
                        <div class="flex justify-center mb-2">
                            @for($i = 1; $i <= 5; $i++)
                                <i class="fas fa-star {{ $i <= round($stats['average_rating']) ? 'text-yellow-400' : 'text-gray-300' }}"></i>
                            @endfor
                        </div>
                        <div class="text-gray-600 text-sm">На основе {{ $stats['total'] }} отзывов</div>
                    </div>
                    
                    <div class="space-y-2">
                        @for($rating = 5; $rating >= 1; $rating--)
                            <div class="flex items-center">
                                <span class="w-12 text-sm text-gray-600">{{ $rating }} ★</span>
                                <div class="flex-1 bg-gray-200 rounded-full h-2 mx-2">
                                    <div class="bg-yellow-400 h-2 rounded-full" 
                                         style="width: {{ $stats['total'] > 0 ? ($stats['rating_distribution'][$rating] / $stats['total']) * 100 : 0 }}%"></div>
                                </div>
                                <span class="w-8 text-sm text-gray-600">{{ $stats['rating_distribution'][$rating] }}</span>
                            </div>
                        @endfor
                    </div>
                </div>
            </div>

            <!-- Фильтры -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h3 class="text-lg font-semibold mb-4">Фильтры</h3>
                
                <form method="GET" action="{{ route('reviews.index') }}" class="space-y-4">
                    <!-- Рейтинг -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Рейтинг</label>
                        <select name="rating" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Все рейтинги</option>
                            <option value="5" {{ request('rating') == '5' ? 'selected' : '' }}>5 звезд</option>
                            <option value="4" {{ request('rating') == '4' ? 'selected' : '' }}>4 звезды</option>
                            <option value="3" {{ request('rating') == '3' ? 'selected' : '' }}>3 звезды</option>
                            <option value="2" {{ request('rating') == '2' ? 'selected' : '' }}>2 звезды</option>
                            <option value="1" {{ request('rating') == '1' ? 'selected' : '' }}>1 звезда</option>
                        </select>
                    </div>

                    <!-- Тур -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Тур</label>
                        <select name="tour_id" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Все туры</option>
                            @foreach($tours as $tour)
                                <option value="{{ $tour->tour_id }}" {{ request('tour_id') == $tour->tour_id ? 'selected' : '' }}>
                                    {{ $tour->title }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Сортировка -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Сортировка</label>
                        <select name="sort" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            <option value="created_at" {{ request('sort') == 'created_at' ? 'selected' : '' }}>По дате (новые)</option>
                            <option value="rating" {{ request('sort') == 'rating' ? 'selected' : '' }}>По рейтингу (высокий)</option>
                        </select>
                    </div>

                    <div class="flex space-x-2">
                        <button type="submit" class="flex-1 bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition text-sm font-semibold">
                            Применить
                        </button>
                        <a href="{{ route('reviews.index') }}" class="bg-gray-300 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-400 transition text-sm font-semibold">
                            Сбросить
                        </a>
                    </div>
                </form>
            </div>

            <!-- Призыв к действию -->
            @auth
                <div class="bg-blue-50 rounded-xl p-6 mt-6">
                    <h4 class="font-semibold text-blue-800 mb-2">Поделитесь впечатлениями!</h4>
                    <p class="text-blue-700 text-sm mb-4">Оставьте отзыв о вашем путешествии</p>
                    <a href="{{ route('tours.index') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition text-sm font-semibold block text-center">
                        Найти тур
                    </a>
                </div>
            @else
                <div class="bg-gray-50 rounded-xl p-6 mt-6">
                    <h4 class="font-semibold text-gray-800 mb-2">Хотите оставить отзыв?</h4>
                    <p class="text-gray-600 text-sm mb-4">Войдите в систему, чтобы поделиться своими впечатлениями</p>
                    <a href="{{ route('login') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition text-sm font-semibold block text-center">
                        Войти
                    </a>
                </div>
            @endauth
        </div>

        <!-- Основной контент -->
        <div class="lg:col-span-3">
            <!-- Информация о результатах -->
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6">
                <p class="text-gray-600 mb-2 md:mb-0">
                    Найдено <span class="font-semibold">{{ $reviews->total() }}</span> отзывов
                    @if(request('rating') || request('tour_id'))
                        по выбранным фильтрам
                    @endif
                </p>
                
                @if(request('rating') || request('tour_id') || request('sort'))
                <a href="{{ route('reviews.index') }}" class="text-blue-600 hover:text-blue-500 text-sm font-medium">
                    <i class="fas fa-times mr-1"></i>Сбросить фильтры
                </a>
                @endif
            </div>

            <!-- Список отзывов -->
            @if($reviews->count() > 0)
                <div class="space-y-6">
                    @foreach($reviews as $review)
                    <div class="bg-white rounded-xl shadow-lg p-6">
                        <div class="flex flex-col md:flex-row md:items-start gap-4">
                            <!-- Аватар и информация о клиенте -->
                            <div class="flex-shrink-0">
                                <div class="w-12 h-12 bg-blue-600 rounded-full flex items-center justify-center text-white font-semibold text-lg">
                                    {{ substr($review->client->first_name, 0, 1) }}{{ substr($review->client->last_name, 0, 1) }}
                                </div>
                            </div>
                            
                            <!-- Основное содержание -->
                            <div class="flex-1">
                                <!-- Заголовок -->
                                <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-3">
                                    <div>
                                        <h3 class="font-semibold text-gray-900 text-lg">
                                            {{ $review->client->first_name }} {{ $review->client->last_name }}
                                        </h3>
                                        <p class="text-gray-500 text-sm">
                                            {{ $review->created_at->translatedFormat('d F Y') }}
                                        </p>
                                    </div>
                                    <div class="flex items-center mt-2 md:mt-0">
                                        <div class="flex text-yellow-400 mr-2">
                                            @for($i = 0; $i < $review->rating; $i++)
                                                <i class="fas fa-star"></i>
                                            @endfor
                                            @for($i = $review->rating; $i < 5; $i++)
                                                <i class="far fa-star"></i>
                                            @endfor
                                        </div>
                                        <span class="text-gray-600 text-sm">({{ $review->rating }}/5)</span>
                                    </div>
                                </div>

                                <!-- Информация о туре -->
                                <div class="mb-4 p-3 bg-gray-50 rounded-lg">
                                    <div class="flex items-center">
                                        <img src="{{ $review->tour->image_url }}" alt="{{ $review->tour->title }}" 
                                             class="w-16 h-12 object-cover rounded mr-3">
                                        <div class="flex-1">
                                            <h4 class="font-medium text-gray-900">{{ $review->tour->title }}</h4>
                                            <p class="text-gray-600 text-sm">
                                                <i class="fas fa-map-marker-alt text-red-500 mr-1"></i>
                                                {{ $review->tour->destination_city }}, {{ $review->tour->destination_country }}
                                            </p>
                                        </div>
                                        <a href="{{ route('tours.show', $review->tour->tour_id) }}" 
                                           class="text-blue-600 hover:text-blue-500 text-sm font-medium ml-2">
                                            Подробнее
                                        </a>
                                    </div>
                                </div>

                                <!-- Текст отзыва -->
                                <div class="prose max-w-none">
                                    <p class="text-gray-700 leading-relaxed">{{ $review->comment }}</p>
                                </div>

                                <!-- Отель -->
                                <div class="mt-4 flex items-center text-sm text-gray-600">
                                    <i class="fas fa-hotel text-green-500 mr-2"></i>
                                    <span>Проживание: {{ $review->tour->hotel->name }}</span>
                                    <span class="mx-2">•</span>
                                    <span class="text-yellow-500">
                                        @for($i = 0; $i < $review->tour->hotel->star_rating; $i++)
                                            ★
                                        @endfor
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- Пагинация -->
                <div class="mt-8">
                    {{ $reviews->links() }}
                </div>
            @else
                <div class="bg-white rounded-xl shadow-lg p-12 text-center">
                    <i class="fas fa-comment-slash text-4xl text-gray-400 mb-4"></i>
                    <h3 class="text-xl font-semibold text-gray-600 mb-2">Отзывы не найдены</h3>
                    <p class="text-gray-500 mb-6">
                        @if(request('rating') || request('tour_id'))
                            Попробуйте изменить параметры фильтрации
                        @else
                            Пока нет отзывов. Будьте первым!
                        @endif
                    </p>
                    <a href="{{ route('tours.index') }}" class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition inline-flex items-center">
                        <i class="fas fa-search mr-2"></i>Найти туры
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Стили для красивого отображения текста -->
<style>
    .prose {
        color: #374151;
        max-width: none;
        line-height: 1.75;
    }
    
    .prose p {
        margin-top: 0;
        margin-bottom: 0;
    }
</style>
@endsection