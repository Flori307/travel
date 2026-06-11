<?php
// app/Http/Controllers/ReviewController.php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Tour;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ReviewController extends Controller
{
    public function index(Request $request)
    {
        // Показываем только одобренные отзывы (approved)
        $query = Review::with(['client', 'tour.hotel'])
            ->where('moderation_status', 'approved');

        // Фильтр по рейтингу
        if ($request->has('rating') && $request->rating) {
            $query->where('rating', $request->rating);
        }

        // Фильтр по туру
        if ($request->has('tour_id') && $request->tour_id) {
            $query->where('tour_id', $request->tour_id);
        }

        // Сортировка
        $sort = $request->get('sort', 'created_at');
        $order = $request->get('order', 'desc');

        $allowedSort = ['created_at', 'rating'];
        $allowedOrder = ['asc', 'desc'];

        if (in_array($sort, $allowedSort) && in_array($order, $allowedOrder)) {
            $query->orderBy($sort, $order);
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $reviews = $query->paginate(10);
        $tours = Tour::whereHas('reviews')->with('hotel')->get();
        
        // Статистика отзывов (только одобренные)
        $stats = [
            'total' => Review::where('moderation_status', 'approved')->count(),
            'average_rating' => Review::where('moderation_status', 'approved')->avg('rating') ?? 0,
            'rating_distribution' => [
                5 => Review::where('rating', 5)->where('moderation_status', 'approved')->count(),
                4 => Review::where('rating', 4)->where('moderation_status', 'approved')->count(),
                3 => Review::where('rating', 3)->where('moderation_status', 'approved')->count(),
                2 => Review::where('rating', 2)->where('moderation_status', 'approved')->count(),
                1 => Review::where('rating', 1)->where('moderation_status', 'approved')->count(),
            ]
        ];

        return view('reviews.index', compact('reviews', 'tours', 'stats'));
    }

    public function store(Request $request)
    {
        // Кастомные сообщения на русском языке
        $messages = [
            'tour_id.required' => 'Необходимо выбрать тур для отзыва.',
            'tour_id.exists' => 'Выбранный тур не существует.',
            'rating.required' => 'Пожалуйста, поставьте оценку туру.',
            'rating.integer' => 'Оценка должна быть целым числом.',
            'rating.min' => 'Оценка должна быть не менее 1 звезды.',
            'rating.max' => 'Оценка должна быть не более 5 звёзд.',
            'comment.required' => 'Пожалуйста, напишите комментарий к отзыву.',
            'comment.min' => 'Комментарий должен содержать не менее 10 символов.',
            'comment.max' => 'Комментарий не должен превышать 1000 символов.',
        ];

        $validator = Validator::make($request->all(), [
            'tour_id' => 'required|exists:tours,tour_id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|min:10|max:1000',
        ], $messages);

        // Дополнительная проверка для точного отображения длины комментария
        $validator->after(function ($validator) use ($request) {
            $length = strlen($request->comment);
            if ($length < 10) {
                $validator->errors()->add('comment', 'Комментарий должен содержать не менее 10 символов. Сейчас у вас ' . $length . ' символов.');
            }
        });

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $tour = Tour::findOrFail($request->tour_id);

        // Проверяем, есть ли уже отзыв от этого пользователя
        $existingReview = Review::where('client_id', Auth::id())
            ->where('tour_id', $request->tour_id)
            ->first();

        if ($existingReview) {
            return back()->with('error', 'Вы уже оставляли отзыв на этот тур.');
        }

        // Проверяем, был ли у пользователя этот тур
        if (!$tour->canUserReview(Auth::id())) {
            return back()->with('error', 'Вы можете оставлять отзывы только на туры, которые вы забронировали и которые завершены.');
        }

        // Создаём отзыв со статусом "pending" (ожидает модерации)
        Review::create([
            'client_id' => Auth::id(),
            'tour_id' => $request->tour_id,
            'rating' => $request->rating,
            'comment' => $request->comment,
            'moderation_status' => 'pending', // Отзыв отправлен на модерацию
        ]);

        return back()->with('success', 'Спасибо за ваш отзыв! Он будет опубликован после проверки модератором.');
    }

    // Обновление отзыва (только если отзыв ещё не промодерирован)
    public function update(Request $request, $id)
    {
        $messages = [
            'rating.required' => 'Пожалуйста, поставьте оценку туру.',
            'rating.integer' => 'Оценка должна быть целым числом.',
            'rating.min' => 'Оценка должна быть не менее 1 звезды.',
            'rating.max' => 'Оценка должна быть не более 5 звёзд.',
            'comment.required' => 'Пожалуйста, напишите комментарий к отзыву.',
            'comment.min' => 'Комментарий должен содержать не менее 10 символов.',
            'comment.max' => 'Комментарий не должен превышать 1000 символов.',
        ];

        $validator = Validator::make($request->all(), [
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|min:10|max:1000',
        ], $messages);

        $validator->after(function ($validator) use ($request) {
            $length = strlen($request->comment);
            if ($length < 10) {
                $validator->errors()->add('comment', 'Комментарий должен содержать не менее 10 символов. Сейчас у вас ' . $length . ' символов.');
            }
        });

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Можно редактировать только отзывы со статусом 'pending'
        $review = Review::where('client_id', Auth::id())
            ->where('review_id', $id)
            ->where('moderation_status', 'pending')
            ->firstOrFail();

        $review->update([
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        return back()->with('success', 'Отзыв успешно обновлён! После сохранения он снова уйдёт на модерацию.');
    }

    // Удаление отзыва
    public function destroy($id)
    {
        // Можно удалить любой свой отзыв
        $review = Review::where('client_id', Auth::id())
            ->where('review_id', $id)
            ->firstOrFail();

        $review->delete();

        return back()->with('success', 'Отзыв удалён.');
    }
}