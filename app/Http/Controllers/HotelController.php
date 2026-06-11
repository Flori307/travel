<?php
// app/Http/Controllers/HotelController.php

namespace App\Http\Controllers;

use App\Models\Hotel;
use App\Models\Tour;
use Illuminate\Http\Request;

class HotelController extends Controller
{
    public function index(Request $request)
    {
        $query = Hotel::withCount('tours');

        // Фильтр по стране
        if ($request->has('country') && $request->country) {
            $query->where('country', 'like', '%' . $request->country . '%');
        }

        // Фильтр по городу
        if ($request->has('city') && $request->city) {
            $query->where('city', 'like', '%' . $request->city . '%');
        }

        // Фильтр по звездам
        if ($request->has('stars') && $request->stars) {
            $query->where('star_rating', $request->stars);
        }

        // Сортировка
        $sort = $request->get('sort', 'star_rating');
        $order = $request->get('order', 'desc');

        $allowedSort = ['star_rating', 'name', 'city'];
        $allowedOrder = ['asc', 'desc'];

        if (in_array($sort, $allowedSort) && in_array($order, $allowedOrder)) {
            $query->orderBy($sort, $order);
        } else {
            $query->orderBy('star_rating', 'desc');
        }

        $hotels = $query->paginate(12);
        $countries = Hotel::distinct()->pluck('country');
        $cities = Hotel::distinct()->pluck('city');

        return view('hotels.index', compact('hotels', 'countries', 'cities'));
    }

    public function show($id)
    {
        $hotel = Hotel::with(['tours' => function($query) {
            $query->where('available_spots', '>', 0)
                  ->with(['reviews'])
                  ->orderBy('price', 'asc');
        }])->findOrFail($id);

        // Статистика отеля
        $hotelStats = [
            'total_tours' => $hotel->tours->count(),
            'min_price' => $hotel->tours->min('price') ?? 0,
            'max_price' => $hotel->tours->max('price') ?? 0,
            'average_rating' => $hotel->tours->flatMap->reviews->avg('rating') ?? 0,
        ];

        // Похожие отели (по городу)
        $relatedHotels = Hotel::where('city', $hotel->city)
            ->where('hotel_id', '!=', $id)
            ->withCount('tours')
            ->limit(4)
            ->get();

        return view('hotels.show', compact('hotel', 'hotelStats', 'relatedHotels'));
    }
}