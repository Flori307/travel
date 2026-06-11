<?php
// app/Http/Controllers/HomeController.php

namespace App\Http\Controllers;

use App\Models\Tour;
use App\Models\Review;
use App\Models\Hotel;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // Получаем популярные туры с отношениями
        $featuredTours = Tour::with(['hotel', 'reviews'])
            ->where('available_spots', '>', 0)
            ->orderBy('price', 'asc')
            ->limit(8)
            ->get();

        // Получаем последние отзывы с клиентами и турами
        $reviews = Review::with(['client', 'tour.hotel'])
            ->orderBy('created_at', 'desc')
            ->limit(6)
            ->get();

        // Статистика для страницы
        $stats = [
            'total_tours' => Tour::count(),
            'total_hotels' => Hotel::count(),
            'available_tours' => Tour::where('available_spots', '>', 0)->count(),
            'featured_count' => 6,
        ];

        return view('home', compact('featuredTours', 'reviews', 'stats'));
    }
}