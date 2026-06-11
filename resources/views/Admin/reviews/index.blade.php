{{-- resources/views/admin/reviews/index.blade.php --}}
@extends('layouts.admin')

@section('title', 'Модерация отзывов')

@section('content')
<div class="bg-white rounded-lg shadow">
    <!-- Header -->
    <div class="px-6 py-4 border-b border-gray-200">
        <div>
            <h2 class="text-xl font-semibold text-gray-800">Модерация отзывов</h2>
            <p class="text-sm text-gray-600 mt-1">Управление и модерация отзывов пользователей</p>
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
                <div class="text-2xl font-bold text-yellow-600">{{ $stats['pending'] }}</div>
                <div class="text-xs text-gray-600">На модерации</div>
            </div>
            <div class="text-center">
                <div class="text-2xl font-bold text-green-600">{{ $stats['approved'] }}</div>
                <div class="text-xs text-gray-600">Одобрены</div>
            </div>
            <div class="text-center">
                <div class="text-2xl font-bold text-red-600">{{ $stats['rejected'] }}</div>
                <div class="text-xs text-gray-600">Отклонены</div>
            </div>
            <div class="text-center">
                <div class="text-2xl font-bold text-orange-600">{{ $stats['with_profanity'] }}</div>
                <div class="text-xs text-gray-600">С матами</div>
            </div>
        </div>
    </div>
    
    <!-- Filters -->
    <div class="px-6 py-4 border-b border-gray-200">
        <form method="GET" action="{{ route('admin.reviews') }}" class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Статус</label>
                <select name="moderation_status" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                    <option value="">Все</option>
                    <option value="pending" {{ request('moderation_status') == 'pending' ? 'selected' : '' }}>На модерации</option>
                    <option value="approved" {{ request('moderation_status') == 'approved' ? 'selected' : '' }}>Одобрены</option>
                    <option value="rejected" {{ request('moderation_status') == 'rejected' ? 'selected' : '' }}>Отклонены</option>
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">С матами</label>
                <select name="has_profanity" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                    <option value="">Все</option>
                    <option value="yes" {{ request('has_profanity') == 'yes' ? 'selected' : '' }}>Да</option>
                    <option value="no" {{ request('has_profanity') == 'no' ? 'selected' : '' }}>Нет</option>
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Поиск</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Текст отзыва..." class="w-full border border-gray-300 rounded-lg px-3 py-2">
            </div>
            
            <div class="flex items-end space-x-2">
                <button type="submit" class="flex-1 bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">Фильтр</button>
                <a href="{{ route('admin.reviews') }}" class="flex-1 bg-gray-300 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-400 text-center">Сброс</a>
            </div>
        </form>
    </div>
    
    <!-- Reviews List -->
    <div class="divide-y">
        @forelse($reviews as $review)
        <div class="p-6 hover:bg-gray-50 transition">
            <div class="flex justify-between items-start mb-3">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-blue-500 rounded-full flex items-center justify-center text-white font-bold">
                        {{ substr($review->client->first_name, 0, 1) }}{{ substr($review->client->last_name, 0, 1) }}
                    </div>
                    <div>
                        <p class="font-semibold">{{ $review->client->first_name }} {{ $review->client->last_name }}</p>
                        <p class="text-sm text-gray-500">{{ $review->client->email }}</p>
                    </div>
                </div>
                <div class="text-right">
                    <div class="flex text-yellow-400 mb-1">
                        @for($i = 0; $i < $review->rating; $i++) ★ @endfor
                    </div>
                    <p class="text-xs text-gray-500">{{ $review->created_at->format('d.m.Y H:i') }}</p>
                </div>
            </div>
            
            <div class="ml-13 mb-3">
                <p class="text-gray-700">{{ $review->comment }}</p>
                <p class="text-sm text-gray-500 mt-1">Тур: {{ $review->tour->title }}</p>
            </div>
            
            <div class="flex justify-between items-center">
                <div class="flex space-x-2">
                    @if($review->has_profanity)
                        <span class="px-2 py-1 bg-red-100 text-red-800 rounded-full text-xs">Обнаружены маты</span>
                    @endif
                    <span class="px-2 py-1 rounded-full text-xs
                        @if($review->moderation_status == 'pending') bg-yellow-100 text-yellow-800
                        @elseif($review->moderation_status == 'approved') bg-green-100 text-green-800
                        @else bg-red-100 text-red-800 @endif">
                        @if($review->moderation_status == 'pending')
                            На модерации
                        @elseif($review->moderation_status == 'approved')
                            Одобрен
                        @else
                            Отклонен
                        @endif
                    </span>
                </div>
                
                <div class="flex space-x-2">
                    <a href="{{ route('admin.reviews.show', $review->review_id) }}" class="bg-blue-600 text-white px-4 py-1 rounded-lg hover:bg-blue-700 text-sm">
                        Модерировать
                    </a>
                    <form action="{{ route('admin.reviews.delete', $review->review_id) }}" method="POST" class="inline" onsubmit="return confirm('Удалить отзыв?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="bg-red-600 text-white px-4 py-1 rounded-lg hover:bg-red-700 text-sm">
                            Удалить
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @empty
        <div class="p-12 text-center text-gray-500">
            <i class="fas fa-star text-4xl mb-3 block"></i>
            <p>Отзывы не найдены</p>
        </div>
        @endforelse
    </div>
    
    <div class="px-6 py-4 border-t">
        {{ $reviews->withQueryString()->links() }}
    </div>
</div>
@endsection