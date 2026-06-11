{{-- resources/views/admin/users/index.blade.php --}}
@extends('layouts.admin')

@section('title', 'Управление пользователями')

@section('content')
<div class="bg-white rounded-lg shadow">
    <!-- Header -->
    <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
        <div>
            <h2 class="text-xl font-semibold text-gray-800">Пользователи</h2>
            <p class="text-sm text-gray-600 mt-1">Управление пользователями системы</p>
        </div>
    </div>
    
    <!-- Filters -->
    <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
        <form method="GET" action="{{ route('admin.users') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Поиск</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Имя, email..." class="w-full border border-gray-300 rounded-lg px-3 py-2">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Роль</label>
                <select name="role" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                    <option value="">Все роли</option>
                    <option value="user" {{ request('role') == 'user' ? 'selected' : '' }}>Пользователь</option>
                    <option value="moderator" {{ request('role') == 'moderator' ? 'selected' : '' }}>Модератор</option>
                    <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Администратор</option>
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Статус</label>
                <select name="status" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                    <option value="">Все статусы</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Активные</option>
                    <option value="blocked" {{ request('status') == 'blocked' ? 'selected' : '' }}>Заблокированные</option>
                </select>
            </div>
            
            <div class="flex items-end space-x-2">
                <button type="submit" class="flex-1 bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">Фильтр</button>
                <a href="{{ route('admin.users') }}" class="flex-1 bg-gray-300 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-400 text-center">Сброс</a>
            </div>
        </form>
    </div>
    
    <!-- Users Table -->
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">ID</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Пользователь</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Email</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Телефон</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Роль</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Статус</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Действия</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($users as $user)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 text-sm">{{ $user->client_id }}</td>
                    <td class="px-6 py-4">
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center text-white text-sm font-bold">
                                {{ substr($user->first_name, 0, 1) }}{{ substr($user->last_name, 0, 1) }}
                            </div>
                            <div class="ml-3">
                                <div class="text-sm font-medium">{{ $user->first_name }} {{ $user->last_name }}</div>
                                <div class="text-xs text-gray-500">ID: {{ $user->client_id }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-sm">{{ $user->email }}</td>
                    <td class="px-6 py-4 text-sm">{{ $user->phone }}</td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 text-xs rounded-full 
                            @if($user->role == 'admin') bg-purple-100 text-purple-800
                            @elseif($user->role == 'moderator') bg-blue-100 text-blue-800
                            @else bg-gray-100 text-gray-800 @endif">
                            {{ $user->role_name }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        @if($user->is_blocked)
                            <span class="px-2 py-1 bg-red-100 text-red-800 rounded-full text-xs">Заблокирован</span>
                        @else
                            <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs">Активен</span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex space-x-2">
                            <a href="{{ route('admin.users.show', $user->client_id) }}" class="text-blue-600 hover:text-blue-900">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('admin.users.edit', $user->client_id) }}" class="text-green-600 hover:text-green-900">
                                <i class="fas fa-edit"></i>
                            </a>
                            
                            @if(!$user->is_blocked && $user->role != 'admin' && $user->client_id != auth()->id())
                                <button type="button" onclick="showBlockModal({{ $user->client_id }}, '{{ addslashes($user->first_name . ' ' . $user->last_name) }}')" 
                                        class="text-orange-600 hover:text-orange-900">
                                    <i class="fas fa-ban"></i>
                                </button>
                            @endif
                            
                            @if($user->is_blocked)
                                <form action="{{ route('admin.users.unblock', $user->client_id) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="text-green-600 hover:text-green-900" onclick="return confirm('Разблокировать пользователя?')">
                                        <i class="fas fa-check-circle"></i>
                                    </button>
                                </form>
                            @endif
                            
                            @if($user->role != 'admin' && $user->client_id != auth()->id())
                                <form action="{{ route('admin.users.delete', $user->client_id) }}" method="POST" class="inline" 
                                      onsubmit="return confirm('Удалить пользователя? Это действие нельзя отменить!')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                        <i class="fas fa-users text-4xl mb-3 block"></i>
                        <p>Пользователи не найдены</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div class="px-6 py-4 border-t">
        {{ $users->withQueryString()->links() }}
    </div>
</div>

<!-- Modal for block reason -->
<div id="blockModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
                <i class="fas fa-ban text-red-600 text-xl"></i>
            </div>
            <h3 class="text-lg font-medium text-gray-900 text-center mt-4">Блокировка пользователя</h3>
            <div class="mt-2 px-7 py-3">
                <p class="text-sm text-gray-500 mb-3">Вы уверены, что хотите заблокировать пользователя <strong id="blockUserName"></strong>?</p>
                <textarea id="blockReason" rows="3" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm" placeholder="Укажите причину блокировки..."></textarea>
            </div>
            <div class="flex justify-center space-x-3 mt-4">
                <button onclick="closeBlockModal()" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400">Отмена</button>
                <button onclick="submitBlock()" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">Заблокировать</button>
            </div>
        </div>
    </div>
</div>

<form id="blockForm" method="POST" style="display: none;">
    @csrf
    <input type="hidden" name="block_reason" id="blockReasonInput">
</form>

<script>
    let currentUserId = null;
    
    function showBlockModal(userId, userName) {
        currentUserId = userId;
        document.getElementById('blockUserName').textContent = userName;
        document.getElementById('blockModal').classList.remove('hidden');
    }
    
    function closeBlockModal() {
        document.getElementById('blockModal').classList.add('hidden');
        document.getElementById('blockReason').value = '';
        currentUserId = null;
    }
    
    function submitBlock() {
        const reason = document.getElementById('blockReason').value;
        if (!reason.trim()) {
            alert('Пожалуйста, укажите причину блокировки');
            return;
        }
        
        const form = document.getElementById('blockForm');
        form.action = '/admin/users/' + currentUserId + '/block';
        document.getElementById('blockReasonInput').value = reason;
        form.submit();
    }
    
    // Закрытие по Escape
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closeBlockModal();
        }
    });
</script>
@endsection