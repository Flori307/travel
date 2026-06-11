<!-- resources/views/tours/show.blade.php -->
@extends('layouts.app')

@section('title', $tour->title . ' - TravelDream')

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
                <a href="{{ route('tours.index') }}" class="text-gray-500 hover:text-gray-700">Туры</a>
            </li>
            <li>
                <i class="fas fa-chevron-right text-gray-400 text-xs"></i>
            </li>
            <li>
                <span class="text-gray-900 font-medium">{{ $tour->title }}</span>
            </li>
        </ol>
    </nav>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Основная информация -->
        <div class="lg:col-span-2">
            <!-- Галерея -->
            <div class="bg-white rounded-xl shadow-lg overflow-hidden mb-6">
                <img src="{{ $tour->image_url }}" alt="{{ $tour->title }}" class="w-full h-96 object-cover">
            </div>

            <!-- Информация о туре -->
            <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
                <h1 class="text-3xl font-bold text-gray-900 mb-4">{{ $tour->title }}</h1>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div class="space-y-3">
                        <div class="flex items-center">
                            <i class="fas fa-map-marker-alt text-red-500 mr-3 w-5"></i>
                            <div>
                                <div class="font-semibold">{{ $tour->destination_city }}, {{ $tour->destination_country }}</div>
                                <div class="text-sm text-gray-600">{{ $tour->hotel->full_address }}</div>
                            </div>
                        </div>
                        
                        <div class="flex items-center">
                            <i class="fas fa-calendar text-blue-500 mr-3 w-5"></i>
                            <div>
                                <div class="font-semibold">{{ $tour->duration }} дней / {{ $tour->duration - 1 }} ночей</div>
                                <div class="text-sm text-gray-600">
                                    {{ $tour->start_date->format('d.m.Y') }} - {{ $tour->end_date->format('d.m.Y') }}
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="space-y-3">
                        <div class="flex items-center">
                            <i class="fas fa-hotel text-green-500 mr-3 w-5"></i>
                            <div>
                                <div class="font-semibold">{{ $tour->hotel->name }}</div>
                                <div class="text-sm text-gray-600">
                                    @for($i = 0; $i < $tour->hotel->star_rating; $i++)
                                        ★
                                    @endfor
                                    {{ $tour->hotel->star_rating }} звезд
                                </div>
                            </div>
                        </div>
                        
                        <div class="flex items-center">
                            <i class="fas fa-users text-purple-500 mr-3 w-5"></i>
                            <div>
                                <div class="font-semibold">Доступно мест: {{ $tour->available_spots }}</div>
                                <div class="text-sm text-gray-600">Группа до 20 человек</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Описание -->
                <div class="mb-6">
                    <h2 class="text-xl font-semibold mb-3">Описание тура</h2>
                    <p class="text-gray-700 leading-relaxed">{{ $tour->description }}</p>
                </div>

                <!-- Что включено -->
                <div class="mb-6">
                    <h2 class="text-xl font-semibold mb-3">Что включено</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                        <div class="flex items-center">
                            <i class="fas fa-check text-green-500 mr-2"></i>
                            <span>Проживание в отеле</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-check text-green-500 mr-2"></i>
                            <span>Завтраки</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-check text-green-500 mr-2"></i>
                            <span>Трансфер из аэропорта</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-check text-green-500 mr-2"></i>
                            <span>Экскурсии по программе</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-check text-green-500 mr-2"></i>
                            <span>Медицинская страховка</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-check text-green-500 mr-2"></i>
                            <span>Услуги гида</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Отзывы -->
<div class="bg-white rounded-xl shadow-lg p-6">
    <h2 class="text-2xl font-semibold mb-6">Отзывы путешественников</h2>
    
    @if($reviewsStats['total'] > 0)
        <!-- Статистика отзывов -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <div class="text-center">
                <div class="text-4xl font-bold text-gray-900 mb-2">{{ number_format($reviewsStats['average'], 1) }}</div>
                <div class="flex justify-center mb-2">
                    @for($i = 1; $i <= 5; $i++)
                        <i class="fas fa-star {{ $i <= round($reviewsStats['average']) ? 'text-yellow-400' : 'text-gray-300' }}"></i>
                    @endfor
                </div>
                <div class="text-gray-600">На основе {{ $reviewsStats['total'] }} отзывов</div>
            </div>
            
            <div class="space-y-2">
                @for($rating = 5; $rating >= 1; $rating--)
                    <div class="flex items-center">
                        <span class="w-12 text-sm text-gray-600">{{ $rating }} ★</span>
                        <div class="flex-1 bg-gray-200 rounded-full h-2 mx-2">
                            <div class="bg-yellow-400 h-2 rounded-full" 
                                 style="width: {{ $reviewsStats['total'] > 0 ? ($reviewsStats['distribution'][$rating] / $reviewsStats['total']) * 100 : 0 }}%"></div>
                        </div>
                        <span class="w-8 text-sm text-gray-600">{{ $reviewsStats['distribution'][$rating] }}</span>
                    </div>
                @endfor
            </div>
        </div>

        <!-- Список отзывов -->
        <div class="space-y-6">
            @foreach($tour->reviews as $review)
            <div class="border-b border-gray-200 pb-6 last:border-b-0">
                <div class="flex items-start justify-between mb-3">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-blue-600 rounded-full flex items-center justify-center text-white font-semibold mr-3">
                            {{ substr($review->client->first_name, 0, 1) }}{{ substr($review->client->last_name, 0, 1) }}
                        </div>
                        <div>
                            <div class="font-semibold">{{ $review->client->first_name }} {{ $review->client->last_name }}</div>
                            <div class="text-sm text-gray-500">{{ $review->created_at->format('d.m.Y') }}</div>
                        </div>
                    </div>
                    <div class="flex text-yellow-400">
                        @for($i = 0; $i < $review->rating; $i++)
                            <i class="fas fa-star"></i>
                        @endfor
                        @for($i = $review->rating; $i < 5; $i++)
                            <i class="far fa-star"></i>
                        @endfor
                    </div>
                </div>
                <p class="text-gray-700">{{ $review->comment }}</p>
                
                <!-- Кнопка редактирования для своего отзыва -->
                @auth
                    @if($review->client_id == Auth::id())
                        <div class="mt-2 flex space-x-2">
                            <button type="button" 
                                    onclick="editReview({{ $review->review_id }})"
                                    class="text-blue-600 hover:text-blue-800 text-sm">
                                <i class="fas fa-edit mr-1"></i>Редактировать
                            </button>
                            <form method="POST" action="{{ route('reviews.destroy', $review->review_id) }}" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="text-red-600 hover:text-red-800 text-sm"
                                        onclick="return confirm('Удалить отзыв?')">
                                    <i class="fas fa-trash mr-1"></i>Удалить
                                </button>
                            </form>
                        </div>
                    @endif
                @endauth
            </div>
            @endforeach
        </div>
    @else
        <div class="text-center py-8">
            <i class="fas fa-comment text-4xl text-gray-400 mb-4"></i>
            <p class="text-gray-600">Пока нет отзывов об этом туре.</p>
            <p class="text-gray-500 text-sm mt-2">Будьте первым, кто оставит отзыв!</p>
        </div>
    @endif

    <!-- Форма отзыва -->
    @auth
        @if($canReview)
            <div class="mt-8 pt-6 border-t border-gray-200" id="review-form-section">
                <h3 class="text-lg font-semibold mb-4">
                    @if($userReview)
                        Редактировать ваш отзыв
                    @else
                        Оставить отзыв
                    @endif
                </h3>
                
                @if($errors->any())
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                        <ul>
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                
                @if(session('error'))
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                        {{ session('error') }}
                    </div>
                @endif
                
                @if(session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                        {{ session('success') }}
                    </div>
                @endif

                @if($userReview)
                    <!-- Форма редактирования -->
                    <form method="POST" action="{{ route('reviews.update', $userReview->review_id) }}" id="edit-review-form">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="tour_id" value="{{ $tour->tour_id }}">
                @else
                    <!-- Форма создания -->
                    <form method="POST" action="{{ route('reviews.store') }}" id="create-review-form">
                        @csrf
                        <input type="hidden" name="tour_id" value="{{ $tour->tour_id }}">
                @endif
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Ваша оценка</label>
                    <div class="flex space-x-1" id="rating-stars">
                        @for($i = 1; $i <= 5; $i++)
                            <button type="button" class="text-2xl text-gray-300 hover:text-yellow-400 rating-star" data-rating="{{ $i }}">
                                <i class="far fa-star"></i>
                            </button>
                        @endfor
                    </div>
                    <input type="hidden" name="rating" id="rating-value" value="{{ $userReview ? $userReview->rating : 5 }}" required>
                </div>
                
                <div class="mb-4">
                    <label for="comment" class="block text-sm font-medium text-gray-700 mb-2">Ваш отзыв</label>
                    <textarea id="comment" name="comment" rows="4" 
                              class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500" 
                              placeholder="Поделитесь своими впечатлениями..." required>{{ $userReview ? $userReview->comment : old('comment') }}</textarea>
                </div>
                
                <div class="flex space-x-3">
                    <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition">
                        <i class="fas fa-paper-plane mr-2"></i>
                        @if($userReview)
                            Обновить отзыв
                        @else
                            Отправить отзыв
                        @endif
                    </button>
                    
                    @if($userReview)
                        <button type="button" 
                                onclick="cancelEdit()"
                                class="bg-gray-300 text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-400 transition">
                            <i class="fas fa-times mr-2"></i>Отмена
                        </button>
                    @endif
                </div>
                </form>
            </div>
        @else
            <div class="mt-6 text-center">
                <p class="text-gray-600">Вы можете оставлять отзывы только на туры, которые вы забронировали.</p>
            </div>
        @endif
    @else
        <div class="mt-6 text-center">
            <p class="text-gray-600">Хотите оставить отзыв?</p>
            <a href="{{ route('login') }}" class="text-blue-600 hover:text-blue-500 font-medium">Войдите в аккаунт</a>
        </div>
    @endauth
</div>

        </div>

        <!-- Боковая панель -->
        <div class="lg:col-span-1">
            <!-- Бронирование -->
            <div class="bg-white rounded-xl shadow-lg p-6 sticky top-6">
                <div class="text-center mb-6">
                    <div class="text-3xl font-bold text-gray-900 mb-2">{{ number_format($tour->price, 0, '', ' ') }} ₽</div>
                    <div class="text-gray-600">за человека</div>
                </div>

                <div class="space-y-4 mb-6">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Длительность:</span>
                        <span class="font-semibold">{{ $tour->duration }} дней</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Даты:</span>
                        <span class="font-semibold">{{ $tour->start_date->format('d.m.Y') }} - {{ $tour->end_date->format('d.m.Y') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Свободные места:</span>
                        <span class="font-semibold {{ $tour->available_spots < 5 ? 'text-red-600' : 'text-green-600' }}">
                            {{ $tour->available_spots }}
                        </span>
                    </div>
                </div>

                @if($tour->available_spots > 0)
                    @auth
                        <form method="POST" action="{{ route('bookings.store') }}" class="space-y-4">
                            @csrf
                            <input type="hidden" name="tour_id" value="{{ $tour->tour_id }}">
                            
                            <div>
                                <label for="number_of_people" class="block text-sm font-medium text-gray-700 mb-2">Количество человек</label>
                                <select id="number_of_people" name="number_of_people" 
                                        class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500" required>
                                    @for($i = 1; $i <= min(4, $tour->available_spots); $i++)
                                        <option value="{{ $i }}">{{ $i }} {{ $i == 1 ? 'человек' : ($i < 5 ? 'человека' : 'человек') }}</option>
                                    @endfor
                                </select>
                            </div>

                            <div class="bg-blue-50 rounded-lg p-4">
                                <div class="flex justify-between text-sm mb-2">
                                    <span>Стоимость:</span>
                                    <span id="total-price">{{ number_format($tour->price, 0, '', ' ') }} ₽</span>
                                </div>
                                <div class="text-xs text-gray-600">* Цена указана за одного человека</div>
                            </div>

                            <button type="submit" class="w-full bg-green-600 text-white py-3 rounded-lg hover:bg-green-700 transition font-semibold text-lg">
                                <i class="fas fa-shopping-cart mr-2"></i>Забронировать
                            </button>
                        </form>
                    @else
                        <div class="text-center">
                            <p class="text-gray-600 mb-4">Для бронирования необходимо войти в систему</p>
                            <a href="{{ route('login') }}" class="block w-full bg-blue-600 text-white py-3 rounded-lg hover:bg-blue-700 transition font-semibold">
                                <i class="fas fa-sign-in-alt mr-2"></i>Войти
                            </a>
                            <div class="mt-3 text-sm">
                                <span class="text-gray-600">Нет аккаунта? </span>
                                <a href="{{ route('register') }}" class="text-blue-600 hover:text-blue-500">Зарегистрироваться</a>
                            </div>
                        </div>
                    @endauth
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-times-circle text-3xl text-red-500 mb-3"></i>
                        <p class="text-gray-600 font-semibold">Мест нет</p>
                        <p class="text-gray-500 text-sm mt-1">Этот тур полностью забронирован</p>
                    </div>
                @endif
            </div>

            
        </div>
    </div>
</div>

<script>
    // Рейтинг звезды
const ratingStars = document.querySelectorAll('.rating-star');
const ratingValue = document.getElementById('rating-value');

function initializeRatingStars() {
    const currentRating = parseInt(ratingValue.value);
    
    ratingStars.forEach(star => {
        star.addEventListener('click', function() {
            const rating = parseInt(this.getAttribute('data-rating'));
            ratingValue.value = rating;
            updateStarsDisplay(rating);
        });

        star.addEventListener('mouseenter', function() {
            const rating = parseInt(this.getAttribute('data-rating'));
            updateStarsDisplay(rating, true);
        });

        star.addEventListener('mouseleave', function() {
            const currentRating = parseInt(ratingValue.value);
            updateStarsDisplay(currentRating);
        });
    });

    // Инициализация отображения
    updateStarsDisplay(currentRating);
}

function updateStarsDisplay(rating, isHover = false) {
    ratingStars.forEach((star, index) => {
        const icon = star.querySelector('i');
        if (index < rating) {
            icon.className = isHover ? 'fas fa-star text-yellow-300' : 'fas fa-star text-yellow-400';
        } else {
            icon.className = 'far fa-star text-gray-300';
        }
    });
}

// Функция для редактирования отзыва
function editReview(reviewId) {
    // Здесь можно реализовать модальное окно или другую логику редактирования
    // Пока просто прокручиваем к форме
    document.getElementById('review-form-section').scrollIntoView({ 
        behavior: 'smooth' 
    });
}

// Функция отмены редактирования
function cancelEdit() {
    window.location.reload();
}

// Инициализация при загрузке страницы
document.addEventListener('DOMContentLoaded', function() {
    initializeRatingStars();
    
    // Расчет общей стоимости
    const pricePerPerson = {{ $tour->price }};
    const peopleSelect = document.getElementById('number_of_people');
    const totalPrice = document.getElementById('total-price');

    if (peopleSelect && totalPrice) {
        peopleSelect.addEventListener('change', function() {
            const people = parseInt(this.value);
            const total = pricePerPerson * people;
            totalPrice.textContent = total.toLocaleString('ru-RU') + ' ₽';
        });
    }
});


    // Расчет общей стоимости
    const pricePerPerson = {{ $tour->price }};
    const peopleSelect = document.getElementById('number_of_people');
    const totalPrice = document.getElementById('total-price');

    if (peopleSelect && totalPrice) {
        peopleSelect.addEventListener('change', function() {
            const people = parseInt(this.value);
            const total = pricePerPerson * people;
            totalPrice.textContent = total.toLocaleString('ru-RU') + ' ₽';
        });
    }
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