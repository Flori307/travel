<!-- resources/views/profile/show.blade.php -->
@extends('layouts.app')

@section('title', 'Профиль - TravelDream')

@section('content')
<div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
    <div class="bg-white shadow rounded-lg">
        <!-- Заголовок профиля -->
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <div class="w-16 h-16 bg-blue-600 rounded-full flex items-center justify-center text-white text-2xl font-bold">
                        {{ substr($client->first_name, 0, 1) }}{{ substr($client->last_name, 0, 1) }}
                    </div>
                    <div class="ml-4">
                        <h1 class="text-2xl font-bold text-gray-900">{{ $client->full_name }}</h1>
                        <p class="text-gray-600">{{ $client->email }}</p>
                    </div>
                </div>
                <a href="{{ route('profile.edit') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                    <i class="fas fa-edit mr-2"></i>Редактировать
                </a>
            </div>
        </div>

        <div class="px-6 py-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Информация о пользователе -->
                <div class="md:col-span-1">
                    <div class="bg-gray-50 rounded-lg p-6">
                        <h3 class="text-lg font-semibold mb-4">Контактная информация</h3>
                        <div class="space-y-3">
                            <div class="flex items-center">
                                <i class="fas fa-envelope text-gray-400 mr-3"></i>
                                <span>{{ $client->email }}</span>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-phone text-gray-400 mr-3"></i>
                                <span>{{ $client->phone }}</span>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-calendar text-gray-400 mr-3"></i>
                                <span>Зарегистрирован: {{ $client->registration_date->format('d.m.Y') }}</span>
                            </div>
                        </div>

                        <div class="mt-6 space-y-2">
                            <a href="{{ route('profile.edit') }}" class="block w-full text-center bg-white border border-blue-600 text-blue-600 py-2 rounded-lg hover:bg-blue-50 transition">
                                <i class="fas fa-edit mr-2"></i>Редактировать профиль
                            </a>
                            <a href="{{ route('profile.change-password') }}" class="block w-full text-center bg-white border border-gray-300 text-gray-700 py-2 rounded-lg hover:bg-gray-50 transition">
                                <i class="fas fa-lock mr-2"></i>Сменить пароль
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Бронирования и отзывы -->
                <div class="md:col-span-2 space-y-6">
                    <!-- Мои бронирования -->
                    <div class="bg-white border border-gray-200 rounded-lg">
                        <div class="px-4 py-3 border-b border-gray-200 flex justify-between items-center">
                            <h3 class="text-lg font-semibold flex items-center">
                                <i class="fas fa-suitcase mr-2 text-blue-600"></i>
                                Мои бронирования
                            </h3>
                            <a href="{{ route('bookings.index') }}" class="text-blue-600 hover:text-blue-500 text-sm font-medium">
                                Посмотреть все
                            </a>
                        </div>
                        <div class="p-4">
                            @if($bookings->count() > 0)
                                <div class="space-y-4">
                                    @foreach($bookings as $booking)
                                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                        <div class="flex-1">
                                            <h4 class="font-semibold">{{ $booking->tour->title }}</h4>
                                            <p class="text-sm text-gray-600">
                                                {{ $booking->tour->destination_city }}, {{ $booking->tour->destination_country }}
                                            </p>
                                            <p class="text-sm text-gray-500">
                                                {{ $booking->booking_date->format('d.m.Y') }} • 
                                                {{ $booking->number_of_people }} чел. • 
                                                {{ number_format($booking->total_price, 0, '', ' ') }} ₽
                                            </p>
                                        </div>
                                        <span class="px-3 py-1 rounded-full text-sm font-medium 
                                            @if($booking->status === 'confirmed') bg-green-100 text-green-800
                                            @elseif($booking->status === 'pending') bg-yellow-100 text-yellow-800
                                            @elseif($booking->status === 'completed') bg-purple-100 text-purple-800
                                            @else bg-red-100 text-red-800 @endif">
                                            @if($booking->status === 'confirmed')
                                                Подтверждено
                                            @elseif($booking->status === 'pending')
                                                Ожидание
                                            @elseif($booking->status === 'completed')
                                                Завершено
                                            @else
                                                Отменено
                                            @endif
                                        </span>
                                    </div>
                                    @endforeach
                                </div>
                                <div class="mt-4 text-center">
                                    <a href="{{ route('bookings.index') }}" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition inline-flex items-center">
                                        <i class="fas fa-list mr-2"></i>Все бронирования
                                    </a>
                                </div>
                            @else
                                <p class="text-gray-600 text-center py-4">У вас пока нет бронирований.</p>
                                <div class="text-center">
                                    <a href="{{ route('tours.index') }}" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition">
                                        <i class="fas fa-search mr-2"></i>Найти тур
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Мои отзывы -->
                    <div class="bg-white border border-gray-200 rounded-lg">
                        <div class="px-4 py-3 border-b border-gray-200">
                            <h3 class="text-lg font-semibold flex items-center">
                                <i class="fas fa-star mr-2 text-yellow-500"></i>
                                Мои отзывы
                            </h3>
                        </div>
                        <div class="p-4">
                            @if($reviews->count() > 0)
                                <div class="space-y-4">
                                    @foreach($reviews as $review)
                                    <div class="p-3 bg-gray-50 rounded-lg">
                                        <div class="flex items-center justify-between mb-2">
                                            <h4 class="font-semibold">{{ $review->tour->title }}</h4>
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
                                        <p class="text-sm text-gray-500 mt-2">
                                            {{ $review->created_at->format('d.m.Y H:i') }}
                                        </p>
                                    </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-gray-600 text-center py-4">Вы еще не оставляли отзывов.</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection