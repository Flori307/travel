{{-- resources/views/admin/tours/index.blade.php --}}
@extends('layouts.admin')

@section('title', 'Управление турами')

@section('content')
<div class="bg-white rounded-lg shadow">
    <!-- Header -->
    <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
        <div>
            <h2 class="text-xl font-semibold text-gray-800">Туры</h2>
            <p class="text-sm text-gray-600 mt-1">Управление турами и путевками</p>
        </div>
        <a href="{{ route('admin.tours.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
            <i class="fas fa-plus mr-2"></i>Добавить тур
        </a>
    </div>
    
    <!-- Filters -->
    <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
        <form method="GET" action="{{ route('admin.tours') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Поиск</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Название, страна..." class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Статус</label>
                <select name="status" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Все статусы</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Активные</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Неактивные</option>
                </select>
            </div>
            
            <div class="flex items-end space-x-2">
                <button type="submit" class="flex-1 bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                    <i class="fas fa-search mr-2"></i>Фильтр
                </button>
                <a href="{{ route('admin.tours') }}" class="flex-1 bg-gray-300 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-400 transition text-center">
                    <i class="fas fa-times mr-2"></i>Сброс
                </a>
            </div>
        </form>
    </div>
    
    <!-- Tours Table -->
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Изображение</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Название</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Направление</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Цена</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Места</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Дата начала</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Действия</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($tours as $tour)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $tour->tour_id }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <img src="{{ $tour->image_url }}" alt="{{ $tour->title }}" class="w-12 h-12 object-cover rounded-lg">
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-sm font-medium text-gray-900">{{ Str::limit($tour->title, 40) }}</div>
                        <div class="text-xs text-gray-500">{{ $tour->hotel->name ?? 'Нет отеля' }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                        {{ $tour->destination_city }}, {{ $tour->destination_country }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-blue-600">
                        {{ number_format($tour->price, 0, '', ' ') }} ₽
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                        <span class="{{ $tour->available_spots < 5 ? 'text-red-600 font-bold' : 'text-gray-600' }}">
                            {{ $tour->available_spots }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                        {{ $tour->start_date->format('d.m.Y') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <div class="flex space-x-2">
                            <a href="{{ route('tours.show', $tour->tour_id) }}" target="_blank" class="text-blue-600 hover:text-blue-900 transition" title="Просмотр на сайте">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('admin.tours.edit', $tour->tour_id) }}" class="text-green-600 hover:text-green-900 transition" title="Редактировать">
                                <i class="fas fa-edit"></i>
                            </a>
                            
                            <!-- Форма для удаления -->
                            <form action="{{ route('admin.tours.delete', $tour->tour_id) }}" method="POST" class="inline delete-form" data-tour-name="{{ $tour->title }}" onsubmit="return confirmDelete(this)">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900 transition" title="Удалить тур">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-6 py-12 text-center text-gray-500">
                        <i class="fas fa-plane text-4xl mb-3 block"></i>
                        <p class="text-lg">Туры не найдены</p>
                        <a href="{{ route('admin.tours.create') }}" class="mt-4 inline-block bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                            <i class="fas fa-plus mr-2"></i>Добавить первый тур
                        </a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <!-- Pagination -->
    @if($tours->hasPages())
    <div class="px-6 py-4 border-t border-gray-200">
        {{ $tours->withQueryString()->links() }}
    </div>
    @endif
</div>

<script>
    function confirmDelete(form) {
        const tourName = form.dataset.tourName;
        return confirm(`Вы уверены, что хотите удалить тур "${tourName}"? Это действие нельзя отменить! Все связанные бронирования и отзывы также будут удалены.`);
    }
</script>

<style>
    .transition {
        transition: all 0.2s ease;
    }
</style>
@endsection