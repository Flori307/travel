{{-- resources/views/admin/dashboard.blade.php --}}
@extends('layouts.admin')

@section('title', 'Дашборд')

@section('content')
<div class="space-y-6">
    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white rounded-lg shadow p-6 stat-card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Пользователи</p>
                    <p class="text-2xl font-bold">{{ $stats['total_users'] }}</p>
                    <p class="text-xs text-green-600 mt-1">Активных: {{ $stats['active_users'] }}</p>
                    <p class="text-xs text-red-600">Заблокировано: {{ $stats['blocked_users'] }}</p>
                </div>
                <i class="fas fa-users text-4xl text-blue-500 opacity-50"></i>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6 stat-card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Туры</p>
                    <p class="text-2xl font-bold">{{ $stats['total_tours'] }}</p>
                    <p class="text-xs text-green-600 mt-1">Активных: {{ $stats['active_tours'] }}</p>
                </div>
                <i class="fas fa-plane text-4xl text-green-500 opacity-50"></i>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6 stat-card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Бронирования</p>
                    <p class="text-2xl font-bold">{{ $stats['total_bookings'] }}</p>
                    <p class="text-xs text-blue-600 mt-1">Доход: {{ number_format($stats['total_revenue'], 0, '', ' ') }} ₽</p>
                </div>
                <i class="fas fa-suitcase text-4xl text-purple-500 opacity-50"></i>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6 stat-card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Отели</p>
                    <p class="text-2xl font-bold">{{ $stats['total_hotels'] }}</p>
                    
                </div>
                <i class="fas fa-hotel text-4xl text-yellow-500 opacity-50"></i>
            </div>
        </div>
    </div>

    <!-- Recent Bookings & Users -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent Bookings -->
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                <h3 class="text-lg font-semibold">Последние бронирования</h3>
                <a href="{{ route('admin.bookings') }}" class="text-blue-600 text-sm hover:underline">Все →</a>
            </div>
            <div class="p-6">
                @if($recentBookings->count() > 0)
                    <div class="space-y-3">
                        @foreach($recentBookings as $booking)
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                                <div>
                                    <p class="font-semibold text-sm">{{ $booking->client->first_name }} {{ $booking->client->last_name }}</p>
                                    <p class="text-xs text-gray-600">{{ $booking->tour->title }}</p>
                                    <p class="text-xs text-gray-500">{{ $booking->created_at->format('d.m.Y H:i') }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="font-bold text-blue-600">{{ number_format($booking->total_price, 0, '', ' ') }} ₽</p>
                                    <span class="px-2 py-1 text-xs rounded-full 
                                        @if($booking->status == 'confirmed') bg-green-100 text-green-800
                                        @elseif($booking->status == 'pending') bg-yellow-100 text-yellow-800
                                        @elseif($booking->status == 'completed') bg-purple-100 text-purple-800
                                        @else bg-red-100 text-red-800 @endif">
                                        @if($booking->status == 'confirmed')
                                            Подтверждено
                                        @elseif($booking->status == 'pending')
                                            Ожидание
                                        @elseif($booking->status == 'completed')
                                            Завершено
                                        @else
                                            Отменено
                                        @endif
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500 text-center py-4">Нет бронирований</p>
                @endif
            </div>
        </div>

        <!-- Recent Users -->
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                <h3 class="text-lg font-semibold">Новые пользователи</h3>
                <a href="{{ route('admin.users') }}" class="text-blue-600 text-sm hover:underline">Все →</a>
            </div>
            <div class="p-6">
                @if(isset($recentUsers) && $recentUsers->count() > 0)
                    <div class="space-y-3">
                        @foreach($recentUsers as $user)
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                                <div class="flex items-center space-x-3">
                                    <div class="w-10 h-10 bg-blue-500 rounded-full flex items-center justify-center text-white font-bold">
                                        {{ substr($user->first_name, 0, 1) }}{{ substr($user->last_name, 0, 1) }}
                                    </div>
                                    <div>
                                        <p class="font-semibold text-sm">{{ $user->first_name }} {{ $user->last_name }}</p>
                                        <p class="text-xs text-gray-600">{{ $user->email }}</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="text-xs text-gray-500">{{ $user->created_at->format('d.m.Y') }}</p>
                                    @if($user->role == 'admin')
                                        <span class="px-2 py-1 text-xs bg-purple-100 text-purple-800 rounded-full">Администратор</span>
                                    @elseif($user->role == 'moderator')
                                        <span class="px-2 py-1 text-xs bg-blue-100 text-blue-800 rounded-full">Модератор</span>
                                    @else
                                        <span class="px-2 py-1 text-xs bg-gray-100 text-gray-800 rounded-full">Пользователь</span>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500 text-center py-4">Нет новых пользователей</p>
                @endif
            </div>
        </div>
    </div>

    <!-- Pending Reviews -->
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
            <h3 class="text-lg font-semibold">Отзывы на модерации</h3>
            <a href="{{ route('admin.reviews', ['moderation_status' => 'pending']) }}" class="text-blue-600 text-sm hover:underline">Все →</a>
        </div>
        <div class="p-6">
            @if($pendingReviews->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($pendingReviews as $review)
                        <div class="p-4 bg-gray-50 rounded-lg">
                            <div class="flex justify-between items-start mb-2">
                                <div>
                                    <p class="font-semibold">{{ $review->client->first_name }} {{ $review->client->last_name }}</p>
                                    <p class="text-xs text-gray-600">{{ $review->tour->title }}</p>
                                </div>
                                <div class="flex text-yellow-400">
                                    @for($i = 0; $i < $review->rating; $i++) ★ @endfor
                                </div>
                            </div>
                            <p class="text-sm text-gray-700 mb-3">{{ Str::limit($review->comment, 100) }}</p>
                            <div class="flex space-x-2">
                                <a href="{{ route('admin.reviews.show', $review->review_id) }}" class="text-blue-600 text-sm hover:underline">Модерировать →</a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-gray-500 text-center py-4">Нет отзывов на модерации</p>
            @endif
        </div>
    </div>
</div>
@endsection