{{-- resources/views/admin/users/show.blade.php --}}
@extends('layouts.admin')

@section('title', 'Просмотр пользователя')

@section('content')
<div class="bg-white rounded-lg shadow">
    <div class="px-6 py-4 border-b border-gray-200">
        <div class="flex justify-between items-center">
            <h1 class="text-2xl font-bold">Пользователь: {{ $user->full_name }}</h1>
            <div class="flex space-x-2">
                <a href="{{ route('admin.users.edit', $user->client_id) }}" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition">
                    <i class="fas fa-edit mr-2"></i>Редактировать
                </a>
                <a href="{{ route('admin.users') }}" class="bg-gray-300 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-400 transition">
                    <i class="fas fa-arrow-left mr-2"></i>Назад
                </a>
            </div>
        </div>
    </div>
    
    <div class="p-6">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Основная информация -->
            <div class="lg:col-span-2">
                <div class="border rounded-lg p-4 mb-6">
                    <h3 class="font-semibold text-lg mb-4">Личная информация</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <p class="text-gray-500 text-sm">Имя</p>
                            <p class="font-medium">{{ $user->first_name }}</p>
                        </div>
                        <div>
                            <p class="text-gray-500 text-sm">Фамилия</p>
                            <p class="font-medium">{{ $user->last_name }}</p>
                        </div>
                        <div>
                            <p class="text-gray-500 text-sm">Email</p>
                            <p class="font-medium">{{ $user->email }}</p>
                        </div>
                        <div>
                            <p class="text-gray-500 text-sm">Телефон</p>
                            <p class="font-medium">{{ $user->phone }}</p>
                        </div>
                        <div>
                            <p class="text-gray-500 text-sm">Роль</p>
                            <p class="font-medium">
                                <span class="px-2 py-1 text-xs rounded-full 
                                    @if($user->role == 'admin') bg-purple-100 text-purple-800
                                    @elseif($user->role == 'moderator') bg-blue-100 text-blue-800
                                    @else bg-gray-100 text-gray-800 @endif">
                                    {{ $user->role_name }}
                                </span>
                            </p>
                        </div>
                        <div>
                            <p class="text-gray-500 text-sm">Статус</p>
                            <p>
                                @if($user->is_blocked)
                                    <span class="px-2 py-1 bg-red-100 text-red-800 rounded-full text-xs">Заблокирован</span>
                                    @if($user->block_reason)
                                        <p class="text-xs text-gray-500 mt-1">Причина: {{ $user->block_reason }}</p>
                                    @endif
                                @else
                                    <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs">Активен</span>
                                @endif
                            </p>
                        </div>
                        <div>
                            <p class="text-gray-500 text-sm">Дата регистрации</p>
                            <p class="font-medium">{{ $user->created_at->format('d.m.Y H:i') }}</p>
                        </div>
                    </div>
                </div>
                
                <!-- Статистика -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                    <div class="border rounded-lg p-3 text-center">
                        <div class="text-2xl font-bold text-blue-600">{{ $stats['total_bookings'] }}</div>
                        <div class="text-xs text-gray-500">Всего бронирований</div>
                    </div>
                    <div class="border rounded-lg p-3 text-center">
                        <div class="text-2xl font-bold text-green-600">{{ $stats['active_bookings'] }}</div>
                        <div class="text-xs text-gray-500">Активных</div>
                    </div>
                    <div class="border rounded-lg p-3 text-center">
                        <div class="text-2xl font-bold text-purple-600">{{ number_format($stats['total_spent'], 0, '', ' ') }}</div>
                        <div class="text-xs text-gray-500">Потрачено (₽)</div>
                    </div>
                    <div class="border rounded-lg p-3 text-center">
                        <div class="text-2xl font-bold text-yellow-600">{{ $stats['total_reviews'] }}</div>
                        <div class="text-xs text-gray-500">Отзывов</div>
                    </div>
                </div>
                
                <!-- Последние бронирования -->
                <div class="border rounded-lg p-4 mb-6">
                    <h3 class="font-semibold text-lg mb-4">Последние бронирования</h3>
                    @if($recentBookings->count() > 0)
                        <div class="space-y-3">
                            @foreach($recentBookings as $booking)
                                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                    <div class="flex-1">
                                        <p class="font-medium">{{ $booking->tour->title }}</p>
                                        <p class="text-sm text-gray-600">
                                            {{ $booking->tour->destination_city }}, {{ $booking->tour->destination_country }}
                                        </p>
                                        <p class="text-xs text-gray-500">
                                            {{ $booking->created_at->format('d.m.Y') }} • {{ $booking->number_of_people }} чел.
                                        </p>
                                    </div>
                                    <div class="text-right">
                                        <p class="font-bold text-blue-600">{{ number_format($booking->total_price, 0, '', ' ') }} ₽</p>
                                        <span class="px-2 py-1 text-xs rounded-full 
                                            @if($booking->status == 'confirmed') bg-green-100 text-green-800
                                            @elseif($booking->status == 'pending') bg-yellow-100 text-yellow-800
                                            @elseif($booking->status == 'completed') bg-purple-100 text-purple-800
                                            @else bg-red-100 text-red-800 @endif">
                                            {{ $booking->status }}
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="mt-3 text-center">
                            <a href="{{ route('admin.bookings', ['search' => $user->email]) }}" class="text-blue-600 text-sm hover:underline">Все бронирования →</a>
                        </div>
                    @else
                        <p class="text-gray-500 text-center py-4">Нет бронирований</p>
                    @endif
                </div>
                
                <!-- Последние отзывы -->
                <div class="border rounded-lg p-4">
                    <h3 class="font-semibold text-lg mb-4">Последние отзывы</h3>
                    @if($recentReviews->count() > 0)
                        <div class="space-y-3">
                            @foreach($recentReviews as $review)
                                <div class="p-3 bg-gray-50 rounded-lg">
                                    <div class="flex justify-between items-start mb-2">
                                        <p class="font-medium">{{ $review->tour->title }}</p>
                                        <div class="flex text-yellow-400 text-sm">
                                            @for($i = 0; $i < $review->rating; $i++) ★ @endfor
                                        </div>
                                    </div>
                                    <p class="text-sm text-gray-700">{{ Str::limit($review->comment, 100) }}</p>
                                    <p class="text-xs text-gray-500 mt-2">{{ $review->created_at->format('d.m.Y') }}</p>
                                </div>
                            @endforeach
                        </div>
                        <div class="mt-3 text-center">
                            <a href="{{ route('admin.reviews', ['search' => $user->email]) }}" class="text-blue-600 text-sm hover:underline">Все отзывы →</a>
                        </div>
                    @else
                        <p class="text-gray-500 text-center py-4">Нет отзывов</p>
                    @endif
                </div>
            </div>
            
            <!-- Боковая панель - Действия -->
            <div class="lg:col-span-1">
                <div class="border rounded-lg p-4">
                    <h3 class="font-semibold text-lg mb-4">Действия</h3>
                    <div class="space-y-3">
                        @if(!$user->is_blocked && $user->role != 'admin' && $user->client_id != auth()->id())
                            <button onclick="showBlockModal({{ $user->client_id }}, '{{ addslashes($user->full_name) }}')" 
                                    class="w-full bg-orange-600 text-white px-4 py-2 rounded-lg hover:bg-orange-700 transition">
                                <i class="fas fa-ban mr-2"></i>Заблокировать пользователя
                            </button>
                        @endif
                        
                        @if($user->is_blocked)
                            <form action="{{ route('admin.users.unblock', $user->client_id) }}" method="POST">
                                @csrf
                                <button type="submit" class="w-full bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition">
                                    <i class="fas fa-check-circle mr-2"></i>Разблокировать
                                </button>
                            </form>
                        @endif
                        
                        @if($user->role != 'admin' && $user->client_id != auth()->id())
                            <form action="{{ route('admin.users.delete', $user->client_id) }}" method="POST" 
                                  onsubmit="return confirm('Вы уверены, что хотите удалить пользователя {{ $user->full_name }}? Это действие нельзя отменить!')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-full bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition">
                                    <i class="fas fa-trash mr-2"></i>Удалить пользователя
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal для блокировки -->
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
    
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closeBlockModal();
        }
    });
</script>
@endsection