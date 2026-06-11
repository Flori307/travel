{{-- resources/views/admin/bookings/index.blade.php --}}
@extends('layouts.admin')

@section('title', 'Управление бронированиями')

@section('content')
<div class="bg-white rounded-lg shadow">
    <!-- Header -->
    <div class="px-6 py-4 border-b border-gray-200">
        <div>
            <h2 class="text-xl font-semibold text-gray-800">Бронирования</h2>
            <p class="text-sm text-gray-600 mt-1">Управление бронированиями туров</p>
        </div>
    </div>
    
    <!-- Stats -->
    <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
        <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
            <div class="text-center">
                <div class="text-2xl font-bold text-gray-800">{{ $stats['total'] }}</div>
                <div class="text-xs text-gray-600">Всего</div>
            </div>
            <div class="text-center">
                <div class="text-2xl font-bold text-green-600">{{ $stats['confirmed'] }}</div>
                <div class="text-xs text-gray-600">Подтверждены</div>
            </div>
            <div class="text-center">
                <div class="text-2xl font-bold text-yellow-600">{{ $stats['pending'] }}</div>
                <div class="text-xs text-gray-600">В ожидании</div>
            </div>
            <div class="text-center">
                <div class="text-2xl font-bold text-purple-600">{{ $stats['completed'] }}</div>
                <div class="text-xs text-gray-600">Завершены</div>
            </div>
            <div class="text-center">
                <div class="text-2xl font-bold text-red-600">{{ number_format($stats['total_revenue'], 0, '', ' ') }}</div>
                <div class="text-xs text-gray-600">Доход (₽)</div>
            </div>
        </div>
    </div>
    
    <!-- Filters -->
    <div class="px-6 py-4 border-b border-gray-200">
        <form method="GET" action="{{ route('admin.bookings') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Поиск</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Имя, email..." class="w-full border border-gray-300 rounded-lg px-3 py-2">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Статус</label>
                <select name="status" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                    <option value="">Все статусы</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>В ожидании</option>
                    <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Подтверждены</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Завершены</option>
                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Отменены</option>
                </select>
            </div>
            
            <div class="flex items-end space-x-2">
                <button type="submit" class="flex-1 bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">Фильтр</button>
                <a href="{{ route('admin.bookings') }}" class="flex-1 bg-gray-300 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-400 text-center">Сброс</a>
            </div>
        </form>
    </div>
    
    <!-- Bookings Table -->
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">ID</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Клиент</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Тур</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Кол-во</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Сумма</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Статус</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Дата</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Действия</th>
                </tr>
            </thead>
            <tbody>
                @forelse($bookings as $booking)
                <tr class="border-b hover:bg-gray-50">
                    <td class="px-6 py-4 text-sm">{{ $booking->booking_id }}</td>
                    <td class="px-6 py-4">
                        <div class="text-sm font-medium">{{ $booking->client->first_name }} {{ $booking->client->last_name }}</div>
                        <div class="text-xs text-gray-500">{{ $booking->client->email }}</div>
                    </td>
                    <td class="px-6 py-4 text-sm">{{ $booking->tour->title }}</td>
                    <td class="px-6 py-4 text-sm">{{ $booking->number_of_people }} чел.</td>
                    <td class="px-6 py-4 text-sm font-bold text-blue-600">{{ number_format($booking->total_price, 0, '', ' ') }} ₽</td>
                    <td class="px-6 py-4">
                        <select onchange="updateStatus({{ $booking->booking_id }}, this.value)" class="text-xs rounded-full px-2 py-1 border-0
                            @if($booking->status == 'confirmed') bg-green-100 text-green-800
                            @elseif($booking->status == 'pending') bg-yellow-100 text-yellow-800
                            @elseif($booking->status == 'completed') bg-purple-100 text-purple-800
                            @else bg-red-100 text-red-800 @endif">
                            <option value="pending" {{ $booking->status == 'pending' ? 'selected' : '' }}>В ожидании</option>
                            <option value="confirmed" {{ $booking->status == 'confirmed' ? 'selected' : '' }}>Подтверждено</option>
                            <option value="completed" {{ $booking->status == 'completed' ? 'selected' : '' }}>Завершено</option>
                            <option value="cancelled" {{ $booking->status == 'cancelled' ? 'selected' : '' }}>Отменено</option>
                        </select>
                    </td>
                    <td class="px-6 py-4 text-sm">{{ $booking->created_at->format('d.m.Y') }}</td>
                    <td class="px-6 py-4">
                        <div class="flex space-x-2">
                            <a href="{{ route('admin.bookings.show', $booking->booking_id) }}" class="text-blue-600 hover:text-blue-900">
                                <i class="fas fa-eye"></i>
                            </a>
                            <form action="{{ route('admin.bookings.delete', $booking->booking_id) }}" method="POST" class="inline" onsubmit="return confirm('Удалить бронирование?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-6 py-12 text-center text-gray-500">Бронирования не найдены</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div class="px-6 py-4 border-t">
        {{ $bookings->withQueryString()->links() }}
    </div>
</div>

<form id="statusForm" method="POST" style="display: none;">
    @csrf
    @method('PUT')
</form>

<script>
function updateStatus(bookingId, status) {
    if (confirm('Изменить статус бронирования?')) {
        const form = document.getElementById('statusForm');
        form.action = '/admin/bookings/' + bookingId + '/status';
        form.innerHTML = `
            @csrf
            @method('PUT')
            <input type="hidden" name="status" value="${status}">
        `;
        form.submit();
    } else {
        location.reload();
    }
}
</script>
@endsection