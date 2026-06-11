<!-- resources/views/bookings/show.blade.php -->
@extends('layouts.app')

@section('title', 'Бронирование #' . $booking->booking_id . ' - TravelDream')

@section('content')
<div class="max-w-4xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
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
                <a href="{{ route('bookings.index') }}" class="text-gray-500 hover:text-gray-700">Мои бронирования</a>
            </li>
            <li>
                <i class="fas fa-chevron-right text-gray-400 text-xs"></i>
            </li>
            <li>
                <span class="text-gray-900 font-medium">Бронирование #{{ $booking->booking_id }}</span>
            </li>
        </ol>
    </nav>

    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <!-- Заголовок -->
        <div class="bg-gradient-to-r from-blue-600 to-purple-700 px-6 py-4">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-2xl font-bold text-white mb-1">Бронирование #{{ $booking->booking_id }}</h1>
                    <p class="text-blue-100">Тур: {{ $booking->tour->title }}</p>
                </div>
                <div class="text-right">
                    <div class="text-white text-lg font-semibold">{{ number_format($booking->total_price, 0, '', ' ') }} ₽</div>
                    <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full 
                        @if($booking->status === 'confirmed') bg-green-100 text-green-800
                        @elseif($booking->status === 'pending') bg-yellow-100 text-yellow-800
                        @elseif($booking->status === 'completed') bg-purple-100 text-purple-800
                        @else bg-red-100 text-red-800 @endif">
                        @if($booking->status === 'confirmed') Подтверждено
                        @elseif($booking->status === 'pending') Ожидание подтверждения
                        @elseif($booking->status === 'completed') Завершено
                        @else Отменено @endif
                    </span>
                </div>
            </div>
        </div>

        <div class="p-6">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Информация о туре -->
                <div>
                    <h2 class="text-xl font-semibold mb-4">Информация о туре</h2>
                    
                    <div class="space-y-4">
                        <div class="flex items-start">
                            <i class="fas fa-map-marker-alt text-red-500 mt-1 mr-3 w-5"></i>
                            <div>
                                <div class="font-semibold">Направление</div>
                                <div class="text-gray-600">{{ $booking->tour->destination_city }}, {{ $booking->tour->destination_country }}</div>
                            </div>
                        </div>
                        
                        <div class="flex items-start">
                            <i class="fas fa-calendar text-blue-500 mt-1 mr-3 w-5"></i>
                            <div>
                                <div class="font-semibold">Даты тура</div>
                                <div class="text-gray-600">
                                    {{ $booking->tour->start_date->format('d.m.Y') }} - {{ $booking->tour->end_date->format('d.m.Y') }}
                                    ({{ $booking->tour->duration }} дней)
                                </div>
                            </div>
                        </div>
                        
                        <div class="flex items-start">
                            <i class="fas fa-hotel text-green-500 mt-1 mr-3 w-5"></i>
                            <div>
                                <div class="font-semibold">Отель</div>
                                <div class="text-gray-600">{{ $booking->tour->hotel->name }}</div>
                                <div class="text-sm text-gray-500">
                                    @for($i = 0; $i < $booking->tour->hotel->star_rating; $i++)
                                        ★
                                    @endfor
                                    {{ $booking->tour->hotel->star_rating }} звезд
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Описание тура -->
                    <div class="mt-6">
                        <h3 class="font-semibold mb-2">Описание тура</h3>
                        <p class="text-gray-700 text-sm leading-relaxed">{{ $booking->tour->description }}</p>
                    </div>
                </div>

                <!-- Информация о бронировании -->
                <div>
                    <h2 class="text-xl font-semibold mb-4">Детали бронирования</h2>
                    
                    <div class="space-y-4">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Номер бронирования:</span>
                            <span class="font-semibold">#{{ $booking->booking_id }}</span>
                        </div>
                        
                        <div class="flex justify-between">
                            <span class="text-gray-600">Дата бронирования:</span>
                            <span class="font-semibold">{{ $booking->booking_date->format('d.m.Y H:i') }}</span>
                        </div>
                        
                        <div class="flex justify-between">
                            <span class="text-gray-600">Количество участников:</span>
                            <span class="font-semibold">{{ $booking->number_of_people }} человек</span>
                        </div>
                        
                        <div class="flex justify-between">
                            <span class="text-gray-600">Стоимость за человека:</span>
                            <span class="font-semibold">{{ number_format($booking->tour->price, 0, '', ' ') }} ₽</span>
                        </div>
                        
                        <div class="flex justify-between text-lg font-semibold border-t pt-3">
                            <span>Общая стоимость:</span>
                            <span class="text-blue-600">{{ number_format($booking->total_price, 0, '', ' ') }} ₽</span>
                        </div>
                    </div>

                    <!-- Контактная информация -->
                    <div class="mt-6 p-4 bg-gray-50 rounded-lg">
                        <h3 class="font-semibold mb-3">Контактная информация</h3>
                        <div class="space-y-2 text-sm">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Имя:</span>
                                <span class="font-medium">{{ $booking->client->first_name }} {{ $booking->client->last_name }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Email:</span>
                                <span class="font-medium">{{ $booking->client->email }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Телефон:</span>
                                <span class="font-medium">{{ $booking->client->phone }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Действия -->
                    <div class="mt-6 space-y-3">
                        @if($booking->status === 'confirmed')
                            <form action="{{ route('bookings.cancel', $booking->booking_id) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <button type="submit" 
                                        class="w-full bg-red-600 text-white py-3 rounded-lg hover:bg-red-700 transition font-semibold"
                                        onclick="return confirm('Вы уверены, что хотите отменить бронирование?')">
                                    <i class="fas fa-times mr-2"></i>Отменить бронирование
                                </button>
                            </form>
                        @endif
                        
                        <a href="{{ route('tours.show', $booking->tour->tour_id) }}" 
                           class="block w-full bg-blue-600 text-white py-3 rounded-lg hover:bg-blue-700 transition font-semibold text-center">
                            <i class="fas fa-eye mr-2"></i>Посмотреть тур
                        </a>
                        
                        <a href="{{ route('bookings.index') }}" 
                           class="block w-full bg-gray-300 text-gray-700 py-3 rounded-lg hover:bg-gray-400 transition font-semibold text-center">
                            <i class="fas fa-arrow-left mr-2"></i>Вернуться к списку
                        </a>
                    </div>
                </div>
            </div>

            <!-- Важная информация -->
            <div class="mt-8 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                <h3 class="font-semibold text-yellow-800 mb-2 flex items-center">
                    <i class="fas fa-info-circle mr-2"></i>Важная информация
                </h3>
                <ul class="text-sm text-yellow-700 space-y-1">
                    <li>• Подтверждение бронирования придет на вашу электронную почту</li>
                    <li>• Оплату необходимо произвести в течение 24 часов после бронирования</li>
                    <li>• При отмене бронирования более чем за 7 дней до начала тура - полный возврат средств</li>
                    <li>• Для связи с менеджером звоните: +7 (999) 123-45-67</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection