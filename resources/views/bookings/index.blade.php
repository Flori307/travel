@extends('layouts.app')

@section('title', 'Мои бронирования - TravelDream')

@section('content')
<div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Мои бронирования</h1>
        <p class="text-gray-600">Управляйте своими турами и бронированиями</p>
    </div>

    <!-- Статистика -->
    <div class="grid grid-cols-1 md:grid-cols-5 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow p-6 text-center">
            <div class="text-2xl font-bold text-blue-600 mb-2">{{ $bookingStats['total'] }}</div>
            <div class="text-gray-600">Всего бронирований</div>
        </div>
        <div class="bg-white rounded-lg shadow p-6 text-center">
            <div class="text-2xl font-bold text-yellow-600 mb-2">{{ $bookingStats['pending'] ?? 0 }}</div>
            <div class="text-gray-600">В ожидании</div>
        </div>
        <div class="bg-white rounded-lg shadow p-6 text-center">
            <div class="text-2xl font-bold text-green-600 mb-2">{{ $bookingStats['confirmed'] }}</div>
            <div class="text-gray-600">Подтвержденные</div>
        </div>
        <div class="bg-white rounded-lg shadow p-6 text-center">
            <div class="text-2xl font-bold text-purple-600 mb-2">{{ $bookingStats['completed'] }}</div>
            <div class="text-gray-600">Завершенные</div>
        </div>
        <div class="bg-white rounded-lg shadow p-6 text-center">
            <div class="text-2xl font-bold text-red-600 mb-2">{{ $bookingStats['cancelled'] }}</div>
            <div class="text-gray-600">Отмененные</div>
        </div>
    </div>

    <!-- Фильтры -->
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <div class="flex flex-wrap gap-4 items-center">
            <span class="text-sm font-medium text-gray-700">Фильтр по статусу:</span>
            <a href="{{ route('bookings.index') }}" 
               class="px-4 py-2 rounded-lg text-sm font-medium {{ !request('status') ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                Все
            </a>
            <a href="{{ route('bookings.index', ['status' => 'pending']) }}" 
               class="px-4 py-2 rounded-lg text-sm font-medium {{ request('status') == 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                В ожидании
            </a>
            <a href="{{ route('bookings.index', ['status' => 'confirmed']) }}" 
               class="px-4 py-2 rounded-lg text-sm font-medium {{ request('status') == 'confirmed' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                Подтвержденные
            </a>
            <a href="{{ route('bookings.index', ['status' => 'completed']) }}" 
               class="px-4 py-2 rounded-lg text-sm font-medium {{ request('status') == 'completed' ? 'bg-purple-100 text-purple-800' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                Завершенные
            </a>
            <a href="{{ route('bookings.index', ['status' => 'cancelled']) }}" 
               class="px-4 py-2 rounded-lg text-sm font-medium {{ request('status') == 'cancelled' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                Отмененные
            </a>
        </div>
    </div>

    <!-- Список бронирований -->
    @if($bookings->count() > 0)
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Тур</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Даты</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Участники</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Стоимость</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Статус</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Действия</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($bookings as $booking)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <img class="h-12 w-12 rounded-lg object-cover" src="{{ $booking->tour->image_url }}" alt="{{ $booking->tour->title }}">
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $booking->tour->title }}</div>
                                        <div class="text-sm text-gray-500">{{ $booking->tour->destination_city }}, {{ $booking->tour->destination_country }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $booking->tour->start_date->format('d.m.Y') }}</div>
                                <div class="text-sm text-gray-500">{{ $booking->tour->duration }} дней</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $booking->number_of_people }} чел.</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ number_format($booking->total_price, 0, '', ' ') }} ₽</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    @if($booking->status === 'confirmed') bg-green-100 text-green-800
                                    @elseif($booking->status === 'pending') bg-yellow-100 text-yellow-800
                                    @elseif($booking->status === 'completed') bg-purple-100 text-purple-800
                                    @else bg-red-100 text-red-800 @endif">
                                    @if($booking->status === 'confirmed') Подтверждено
                                    @elseif($booking->status === 'pending') Ожидание подтверждения
                                    @elseif($booking->status === 'completed') Завершено
                                    @else Отменено @endif
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex space-x-2">
                                    <a href="{{ route('bookings.show', $booking->booking_id) }}" 
                                       class="text-blue-600 hover:text-blue-900 transition" title="Подробнее">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    @if($booking->status === 'confirmed' || $booking->status === 'pending')
                                    <form action="{{ route('bookings.cancel', $booking->booking_id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" 
                                                class="text-red-600 hover:text-red-900 transition" 
                                                title="Отменить бронирование"
                                                onclick="return confirm('Вы уверены, что хотите отменить бронирование?')">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Пагинация -->
        <div class="mt-6">
            {{ $bookings->links() }}
        </div>
    @else
        <div class="bg-white rounded-lg shadow p-12 text-center">
            <i class="fas fa-suitcase text-4xl text-gray-400 mb-4"></i>
            <h3 class="text-xl font-semibold text-gray-600 mb-2">Бронирования не найдены</h3>
            <p class="text-gray-500 mb-6">
                @if(request('status'))
                    У вас нет бронирований с выбранным статусом.
                @else
                    У вас пока нет бронирований.
                @endif
            </p>
            <a href="{{ route('tours.index') }}" class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition inline-flex items-center">
                <i class="fas fa-search mr-2"></i>Найти туры
            </a>
        </div>
    @endif
</div>
@endsection