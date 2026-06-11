{{-- resources/views/admin/logs/index.blade.php --}}
@extends('layouts.admin')

@section('title', 'Логи действий')

@section('content')
<div class="bg-white rounded-lg shadow">
    <!-- Header -->
    <div class="px-6 py-4 border-b border-gray-200">
        <div>
            <h2 class="text-xl font-semibold text-gray-800">Логи действий администраторов</h2>
            <p class="text-sm text-gray-600 mt-1">История всех действий в админ-панели</p>
        </div>
    </div>
    
    <!-- Filters -->
    <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
        <form method="GET" action="{{ route('admin.logs') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">IP адрес / Действие</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="IP, действие, информация..." class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Тип действия</label>
                <select name="action_type" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Все типы</option>
                    <option value="user" {{ request('action_type') == 'user' ? 'selected' : '' }}>Пользователи</option>
                    <option value="tour" {{ request('action_type') == 'tour' ? 'selected' : '' }}>Туры</option>
                    <option value="hotel" {{ request('action_type') == 'hotel' ? 'selected' : '' }}>Отели</option>
                    <option value="booking" {{ request('action_type') == 'booking' ? 'selected' : '' }}>Бронирования</option>
                    <option value="review" {{ request('action_type') == 'review' ? 'selected' : '' }}>Отзывы</option>
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Действие</label>
                <select name="action" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Все действия</option>
                    <option value="create" {{ request('action') == 'create' ? 'selected' : '' }}>Создание</option>
                    <option value="update" {{ request('action') == 'update' ? 'selected' : '' }}>Обновление</option>
                    <option value="delete" {{ request('action') == 'delete' ? 'selected' : '' }}>Удаление</option>
                    <option value="block" {{ request('action') == 'block' ? 'selected' : '' }}>Блокировка</option>
                    <option value="unblock" {{ request('action') == 'unblock' ? 'selected' : '' }}>Разблокировка</option>
                </select>
            </div>
            
            <div class="flex items-end space-x-2">
                <button type="submit" class="flex-1 bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                    <i class="fas fa-search mr-2"></i>Фильтр
                </button>
                <a href="{{ route('admin.logs') }}" class="flex-1 bg-gray-300 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-400 transition text-center">
                    <i class="fas fa-times mr-2"></i>Сброс
                </a>
            </div>
        </form>
    </div>
    
    <!-- Logs Table -->
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Администратор</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Тип</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Действие</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Цель (ID)</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">IP адрес</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Дата и время</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Детали</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($logs as $log)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $log->log_id }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($log->admin)
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-purple-500 rounded-full flex items-center justify-center text-white text-xs font-bold">
                                    {{ substr($log->admin->first_name, 0, 1) }}{{ substr($log->admin->last_name, 0, 1) }}
                                </div>
                                <div class="ml-3">
                                    <div class="text-sm font-medium text-gray-900">{{ $log->admin->first_name }} {{ $log->admin->last_name }}</div>
                                    <div class="text-xs text-gray-500">{{ $log->admin->email }}</div>
                                </div>
                            </div>
                        @else
                            <span class="text-gray-400 text-sm">Пользователь удален</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 py-1 text-xs rounded-full 
                            @if($log->action_type == 'user') bg-blue-100 text-blue-800
                            @elseif($log->action_type == 'tour') bg-green-100 text-green-800
                            @elseif($log->action_type == 'hotel') bg-yellow-100 text-yellow-800
                            @elseif($log->action_type == 'booking') bg-purple-100 text-purple-800
                            @elseif($log->action_type == 'review') bg-pink-100 text-pink-800
                            @else bg-gray-100 text-gray-800 @endif">
                            {{ $log->action_type }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 py-1 text-xs rounded-full
                            @if($log->action == 'create') bg-green-100 text-green-800
                            @elseif($log->action == 'update') bg-blue-100 text-blue-800
                            @elseif($log->action == 'delete') bg-red-100 text-red-800
                            @elseif($log->action == 'block') bg-orange-100 text-orange-800
                            @elseif($log->action == 'unblock') bg-teal-100 text-teal-800
                            @else bg-gray-100 text-gray-800 @endif">
                            {{ $log->action }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                        @if($log->target_id)
                            ID: {{ $log->target_id }}
                        @else
                            —
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                        <code class="text-xs">{{ $log->ip_address ?? '—' }}</code>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                        {{ $log->created_at->format('d.m.Y H:i:s') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                        <button onclick="showLogDetails({{ json_encode($log) }})" class="text-blue-600 hover:text-blue-800 transition">
                            <i class="fas fa-info-circle"></i> Подробнее
                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-6 py-12 text-center text-gray-500">
                        <i class="fas fa-history text-4xl mb-3 block"></i>
                        <p>Логи не найдены</p>
                        <p class="text-sm mt-2">Пока нет записей о действиях администраторов</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <!-- Pagination -->
    @if($logs->hasPages())
    <div class="px-6 py-4 border-t border-gray-200">
        {{ $logs->withQueryString()->links() }}
    </div>
    @endif
</div>

<!-- Modal for log details -->
<div id="logModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-full max-w-2xl shadow-lg rounded-md bg-white">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-medium text-gray-900">Детали действия</h3>
            <button onclick="closeLogModal()" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        <div id="logDetails" class="space-y-3 max-h-96 overflow-y-auto">
            <!-- Content will be filled by JavaScript -->
        </div>
        <div class="mt-6 flex justify-end">
            <button onclick="closeLogModal()" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition">Закрыть</button>
        </div>
    </div>
</div>

<script>
    function showLogDetails(log) {
        const modal = document.getElementById('logModal');
        const detailsDiv = document.getElementById('logDetails');
        
        let oldValuesHtml = '';
        let newValuesHtml = '';
        let additionalInfoHtml = '';
        
        if (log.old_values) {
            oldValuesHtml = `
                <div class="border-t pt-3 mt-3">
                    <h4 class="font-semibold text-gray-700 mb-2 flex items-center">
                        <i class="fas fa-history text-orange-500 mr-2"></i>Старые значения:
                    </h4>
                    <pre class="bg-gray-100 p-3 rounded text-xs overflow-x-auto">${JSON.stringify(log.old_values, null, 2)}</pre>
                </div>
            `;
        }
        
        if (log.new_values) {
            newValuesHtml = `
                <div class="border-t pt-3 mt-3">
                    <h4 class="font-semibold text-gray-700 mb-2 flex items-center">
                        <i class="fas fa-save text-green-500 mr-2"></i>Новые значения:
                    </h4>
                    <pre class="bg-gray-100 p-3 rounded text-xs overflow-x-auto">${JSON.stringify(log.new_values, null, 2)}</pre>
                </div>
            `;
        }
        
        if (log.additional_info) {
            additionalInfoHtml = `
                <div class="border-t pt-3 mt-3">
                    <h4 class="font-semibold text-gray-700 mb-2 flex items-center">
                        <i class="fas fa-info-circle text-blue-500 mr-2"></i>Дополнительная информация:
                    </h4>
                    <div class="bg-blue-50 p-3 rounded text-sm">${log.additional_info}</div>
                </div>
            `;
        }
        
        detailsDiv.innerHTML = `
            <div class="grid grid-cols-2 gap-3 text-sm">
                <div class="bg-gray-50 p-2 rounded">
                    <p class="text-gray-500 text-xs">Тип действия</p>
                    <p class="font-medium">${log.action_type}</p>
                </div>
                <div class="bg-gray-50 p-2 rounded">
                    <p class="text-gray-500 text-xs">Действие</p>
                    <p class="font-medium">${log.action}</p>
                </div>
                <div class="bg-gray-50 p-2 rounded">
                    <p class="text-gray-500 text-xs">ID цели</p>
                    <p class="font-medium">${log.target_id || '—'}</p>
                </div>
                <div class="bg-gray-50 p-2 rounded">
                    <p class="text-gray-500 text-xs">IP адрес</p>
                    <p class="font-medium font-mono text-xs">${log.ip_address || '—'}</p>
                </div>
                <div class="col-span-2 bg-gray-50 p-2 rounded">
                    <p class="text-gray-500 text-xs">User Agent</p>
                    <p class="font-medium text-xs break-all">${log.user_agent || '—'}</p>
                </div>
                <div class="col-span-2 bg-gray-50 p-2 rounded">
                    <p class="text-gray-500 text-xs">Время</p>
                    <p class="font-medium">${log.created_at || '—'}</p>
                </div>
            </div>
            ${additionalInfoHtml}
            ${oldValuesHtml}
            ${newValuesHtml}
        `;
        
        modal.classList.remove('hidden');
    }
    
    function closeLogModal() {
        document.getElementById('logModal').classList.add('hidden');
    }
    
    // Close modal on escape key
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closeLogModal();
        }
    });
    
    // Close modal when clicking outside
    document.getElementById('logModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeLogModal();
        }
    });
</script>

<style>
    .transition {
        transition: all 0.2s ease;
    }
</style>
@endsection