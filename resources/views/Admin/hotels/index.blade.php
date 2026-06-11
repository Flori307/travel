{{-- resources/views/admin/hotels/index.blade.php --}}
@extends('layouts.admin')

@section('title', 'Управление отелями')

@section('content')
<div class="bg-white rounded-lg shadow">
    <!-- Header -->
    <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
        <div>
            <h2 class="text-xl font-semibold text-gray-800">Отели</h2>
            <p class="text-sm text-gray-600 mt-1">Управление отелями и гостиницами</p>
        </div>
        <a href="{{ route('admin.hotels.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
            <i class="fas fa-plus mr-2"></i>Добавить отель
        </a>
    </div>
    
    <!-- Filters -->
    <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
        <form method="GET" action="{{ route('admin.hotels') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Поиск</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Название, город..." class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
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
                <a href="{{ route('admin.hotels') }}" class="flex-1 bg-gray-300 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-400 transition text-center">
                    <i class="fas fa-times mr-2"></i>Сброс
                </a>
            </div>
        </form>
    </div>
    
    <!-- Hotels Table -->
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Изображение</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Название</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Расположение</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Звезды</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Номера</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Туров</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Действия</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($hotels as $hotel)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $hotel->hotel_id }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <img src="{{ $hotel->image_url }}" alt="{{ $hotel->name }}" class="w-12 h-12 object-cover rounded">
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-sm font-medium text-gray-900">{{ $hotel->name }}</div>
                        <div class="text-xs text-gray-500">{{ $hotel->email }}</div>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-600">
                        {{ $hotel->city }}, {{ $hotel->country }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex text-yellow-500">
                            @for($i = 0; $i < $hotel->star_rating; $i++) ★ @endfor
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $hotel->room_count }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $hotel->tours_count ?? $hotel->tours->count() }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <div class="flex space-x-2">
                            <a href="{{ route('hotels.show', $hotel->hotel_id) }}" target="_blank" class="text-blue-600 hover:text-blue-900" title="Просмотр">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('admin.hotels.edit', $hotel->hotel_id) }}" class="text-green-600 hover:text-green-900" title="Редактировать">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.hotels.delete', $hotel->hotel_id) }}" method="POST" class="inline" onsubmit="return confirm('Удалить отель? Это действие нельзя отменить! Все связанные туры также будут удалены.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900" title="Удалить">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-6 py-12 text-center text-gray-500">
                        <i class="fas fa-hotel text-4xl mb-3 block"></i>
                        <p>Отели не найдены</p>
                        <a href="{{ route('admin.hotels.create') }}" class="mt-3 inline-block bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">Добавить первый отель</a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <!-- Pagination -->
    <div class="px-6 py-4 border-t border-gray-200">
        {{ $hotels->withQueryString()->links() }}
    </div>
</div>
@endsection