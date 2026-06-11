<?php
// app/Http/Controllers/TourController.php

namespace App\Http\Controllers;

use App\Models\Tour;
use App\Models\Hotel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Добавьте эту строку

class TourController extends Controller
{
    public function index(Request $request)
    {
        $query = Tour::with(['hotel', 'reviews'])
            ->where('available_spots', '>', 0);

        // Фильтр по стране
        if ($request->has('country') && $request->country) {
            $query->where('destination_country', 'like', '%' . $request->country . '%');
        }

        // Фильтр по городу
        if ($request->has('city') && $request->city) {
            $query->where('destination_city', 'like', '%' . $request->city . '%');
        }

        // Фильтр по цене
        if ($request->has('price_min') && $request->price_min) {
            $query->where('price', '>=', $request->price_min);
        }

        if ($request->has('price_max') && $request->price_max) {
            $query->where('price', '<=', $request->price_max);
        }

        // Фильтр по дате начала
        if ($request->has('start_date') && $request->start_date) {
            $query->where('start_date', '>=', $request->start_date);
        }

        // Фильтр по количеству звезд отеля
        if ($request->has('hotel_stars') && $request->hotel_stars) {
            $query->whereHas('hotel', function($q) use ($request) {
                $q->where('star_rating', $request->hotel_stars);
            });
        }

        // Сортировка
        $sort = $request->get('sort', 'start_date');
        $order = $request->get('order', 'asc');

        $allowedSort = ['price', 'start_date', 'created_at'];
        $allowedOrder = ['asc', 'desc'];

        if (in_array($sort, $allowedSort) && in_array($order, $allowedOrder)) {
            $query->orderBy($sort, $order);
        } else {
            $query->orderBy('start_date', 'asc');
        }

        $tours = $query->paginate(12);
        $countries = Tour::distinct()->pluck('destination_country');
        $cities = Tour::distinct()->pluck('destination_city');

        return view('tours.index', compact('tours', 'countries', 'cities'));
    }

    public function show($id)
    {
        $tour = Tour::with([
        'hotel', 
        'reviews' => function($query) {
            $query->where('moderation_status', 'approved')->with('client');
        },
        'bookings'
    ])->findOrFail($id);

        // Похожие туры (по стране)
        $relatedTours = Tour::where('destination_country', $tour->destination_country)
            ->where('tour_id', '!=', $id)
            ->where('available_spots', '>', 0)
            ->with(['hotel', 'reviews'])
            ->limit(4)
            ->get();

        // Статистика отзывов
        $reviewsStats = [
            'total' => $tour->reviews->count(),
            'average' => $tour->reviews->avg('rating') ?? 0,
            'distribution' => [
                5 => $tour->reviews->where('rating', 5)->count(),
                4 => $tour->reviews->where('rating', 4)->count(),
                3 => $tour->reviews->where('rating', 3)->count(),
                2 => $tour->reviews->where('rating', 2)->count(),
                1 => $tour->reviews->where('rating', 1)->count(),
            ]
        ];

        // Проверяем, может ли пользователь оставить отзыв
        $canReview = false;
        $userReview = null;
        
        if (Auth::check()) {
            $canReview = $tour->canUserReview(Auth::id());
            $userReview = $tour->getUserReview(Auth::id());
        }

        return view('tours.show', compact(
            'tour', 
            'relatedTours', 
            'reviewsStats',
            'canReview',
            'userReview'
        ));
    }

    public function search(Request $request)
    {
        $query = Tour::with(['hotel', 'reviews'])
            ->where('available_spots', '>', 0);

        if ($request->has('destination') && $request->destination) {
            $query->where(function($q) use ($request) {
                $q->where('destination_country', 'like', '%' . $request->destination . '%')
                  ->orWhere('destination_city', 'like', '%' . $request->destination . '%')
                  ->orWhere('title', 'like', '%' . $request->destination . '%');
            });
        }

        if ($request->has('price_min') && $request->price_min) {
            $query->where('price', '>=', $request->price_min);
        }

        if ($request->has('price_max') && $request->price_max) {
            $query->where('price', '<=', $request->price_max);
        }

        $tours = $query->orderBy('start_date', 'asc')->paginate(12);

        return view('tours.search', compact('tours'));
    }
}