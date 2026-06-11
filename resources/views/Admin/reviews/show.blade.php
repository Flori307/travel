{{-- resources/views/admin/reviews/show.blade.php --}}
@extends('layouts.admin')

@section('title', 'Модерация отзыва #' . $review->review_id)

@section('content')
<div class="bg-white rounded-lg shadow">
    <div class="px-6 py-4 border-b border-gray-200">
        <div class="flex justify-between items-center">
            <h1 class="text-2xl font-bold">Модерация отзыва #{{ $review->review_id }}</h1>
            <a href="{{ route('admin.reviews') }}" class="bg-gray-300 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-400">Назад</a>
        </div>
    </div>
    
    <div class="p-6">
        <!-- Информация об авторе -->
        <div class="border rounded-lg p-4 mb-6">
            <h3 class="font-semibold text-lg mb-3">Информация об авторе</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <p><strong>Имя:</strong> {{ $review->client->first_name }} {{ $review->client->last_name }}</p>
                    <p><strong>Email:</strong> {{ $review->client->email }}</p>
                </div>
                <div>
                    <p><strong>Дата регистрации:</strong> {{ $review->client->created_at->format('d.m.Y') }}</p>
                    <p><strong>Всего отзывов:</strong> {{ $review->client->reviews->count() }}</p>
                </div>
            </div>
        </div>
        
        <!-- Информация о туре -->
        <div class="border rounded-lg p-4 mb-6">
            <h3 class="font-semibold text-lg mb-3">Информация о туре</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <p><strong>Название:</strong> {{ $review->tour->title }}</p>
                    <p><strong>Направление:</strong> {{ $review->tour->destination_city }}, {{ $review->tour->destination_country }}</p>
                </div>
                <div>
                    <p><strong>Отель:</strong> {{ $review->tour->hotel->name }}</p>
                    <p><strong>Даты:</strong> {{ $review->tour->start_date->format('d.m.Y') }} - {{ $review->tour->end_date->format('d.m.Y') }}</p>
                </div>
            </div>
        </div>
        
        <!-- Содержание отзыва -->
        <div class="border rounded-lg p-4 mb-6">
            <h3 class="font-semibold text-lg mb-3">Содержание отзыва</h3>
            <div class="mb-4">
                <p><strong>Оценка:</strong></p>
                <div class="flex text-yellow-400 text-xl">
                    @for($i = 0; $i < $review->rating; $i++) ★ @endfor
                </div>
            </div>
            <div class="mb-4">
                <p><strong>Текст отзыва:</strong></p>
                <div class="p-4 bg-gray-50 rounded-lg mt-2">
                    <p class="text-gray-800">{{ $review->comment }}</p>
                </div>
            </div>
            @if($review->has_profanity)
                <div class="bg-red-50 border border-red-200 rounded-lg p-3">
                    <p class="text-red-700 text-sm">
                        <i class="fas fa-exclamation-triangle mr-2"></i>
                        Внимание! В отзыве обнаружены нецензурные выражения.
                    </p>
                </div>
            @endif
        </div>
        
        <!-- Форма модерации -->
        <div class="border rounded-lg p-4">
            <h3 class="font-semibold text-lg mb-3">Модерация отзыва</h3>
            
            <form method="POST" action="{{ route('admin.reviews.moderate', $review->review_id) }}">
                @csrf
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Статус модерации</label>
                    <div class="flex space-x-4">
                        <label class="flex items-center space-x-2">
                            <input type="radio" name="moderation_status" value="approved" {{ $review->moderation_status == 'approved' ? 'checked' : '' }} class="text-green-600">
                            <span class="text-green-700">Одобрить</span>
                        </label>
                        <label class="flex items-center space-x-2">
                            <input type="radio" name="moderation_status" value="rejected" {{ $review->moderation_status == 'rejected' ? 'checked' : '' }} class="text-red-600">
                            <span class="text-red-700">Отклонить</span>
                        </label>
                    </div>
                </div>
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Комментарий модератора (необязательно)</label>
                    <textarea name="moderation_comment" rows="3" class="w-full border border-gray-300 rounded-lg px-3 py-2" placeholder="Причина отклонения или примечание...">{{ $review->moderation_comment }}</textarea>
                </div>
                
                <div class="flex justify-end space-x-3">
                    <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700">
                        <i class="fas fa-save mr-2"></i>Сохранить решение
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection