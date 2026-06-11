{{-- resources/views/admin/bookings/show.blade.php --}}
@extends('layouts.admin')

@section('title', 'Бронирование #' . $booking->booking_id)

@section('content')
<div class="bg-white rounded-lg shadow">
    <div class="px-6 py-4 border-b border-gray-200">
        <div class="flex justify-between items-center">
            <h1 class="text-2xl font-bold">Бронирование #{{ $booking->booking_id }}</h1>
            <a href="{{ route('admin.bookings') }}" class="bg-gray-300 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-400">Назад</a>
        </div>
    </div>
    
    <div class="p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Информация о клиенте -->
            <div class="border rounded-lg p-4">
                <h3 class="font-semibold text-lg mb-3">Информация о клиенте</h3>
                <div class="space-y-2">
                    <p><strong>Имя:</strong> {{ $booking->client->first_name }} {{ $booking->client->last_name }}</p>
                    <p><strong>Email:</strong> {{ $booking->client->email }}</p>
                    <p><strong>Телефон:</strong> {{ $booking->client->phone }}</p>
                    <p><strong>Зарегистрирован:</strong> {{ $booking->client->created_at->format('d.m.Y') }}</p>
                </div>
            </div>
            
            <!-- Информация о бронировании -->
            <div class="border rounded-lg p-4">
                <h3 class="font-semibold text-lg mb-3">Детали бронирования</h3>
                <div class="space-y-2">
                    <p><strong>Дата бронирования:</strong> {{ $booking->created_at->format('d.m.Y H:i') }}</p>
                    <p><strong>Количество человек:</strong> {{ $booking->number_of_people }}</p>
                    <p><strong>Общая стоимость:</strong> <span class="font-bold text-blue-600">{{ number_format($booking->total_price, 0, '', ' ') }} ₽</span></p>
                    <p><strong>Статус:</strong> 
                        <span class="px-2 py-1 rounded-full text-xs
                            @if($booking->status == 'confirmed') bg-green-100 text-green-800
                            @elseif($booking->status == 'pending') bg-yellow-100 text-yellow-800
                            @elseif($booking->status == 'completed') bg-purple-100 text-purple-800
                            @else bg-red-100 text-red-800 @endif">
                            {{ $booking->status }}
                        </span>
                    </p>
                </div>
            </div>
            
            <!-- Информация о туре -->
            <div class="md:col-span-2 border rounded-lg p-4">
                <h3 class="font-semibold text-lg mb-3">Информация о туре</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p><strong>Название:</strong> {{ $booking->tour->title }}</p>
                        <p><strong>Направление:</strong> {{ $booking->tour->destination_city }}, {{ $booking->tour->destination_country }}</p>
                        <p><strong>Даты:</strong> {{ $booking->tour->start_date->format('d.m.Y') }} - {{ $booking->tour->end_date->format('d.m.Y') }}</p>
                        <p><strong>Длительность:</strong> {{ $booking->tour->duration }} дней</p>
                    </div>
                    <div>
                        <p><strong>Отель:</strong> {{ $booking->tour->hotel->name }}</p>
                        <p><strong>Цена за человека:</strong> {{ number_format($booking->tour->price, 0, '', ' ') }} ₽</p>
                        <p><strong>Доступно мест:</strong> {{ $booking->tour->available_spots }}</p>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="mt-6 flex justify-end space-x-3">
            <form action="{{ route('admin.bookings.cancel', $booking->booking_id) }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="px-6 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700" onclick="return confirm('Отменить бронирование?')">
                    Отменить бронирование
                </button>
            </form>
        </div>
    </div>
</div>
@endsection