<?php
// app/Http/Controllers/BookingController.php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Tour;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function store(Request $request)
    {
        $request->validate([
            'tour_id' => 'required|exists:tours,tour_id',
            'number_of_people' => 'required|integer|min:1|max:10',
        ]);

        $tour = Tour::findOrFail($request->tour_id);

        // Проверяем доступность мест (для информации, но места пока не списываем)
        if ($tour->available_spots < $request->number_of_people) {
            return back()->with('error', 'Недостаточно свободных мест для бронирования. Доступно: ' . $tour->available_spots . ' мест.');
        }

        // Проверяем, нет ли уже активного бронирования на этот тур
        $existingBooking = Booking::where('client_id', Auth::id())
            ->where('tour_id', $request->tour_id)
            ->whereIn('status', ['pending', 'confirmed'])
            ->first();

        if ($existingBooking) {
            return back()->with('error', 'У вас уже есть активное бронирование на этот тур.');
        }

        // Создаем бронирование со статусом "pending" (ожидает подтверждения)
        $booking = Booking::create([
            'client_id' => Auth::id(),
            'tour_id' => $request->tour_id,
            'booking_date' => now(),
            'number_of_people' => $request->number_of_people,
            'status' => 'pending',  // Изменено с 'confirmed' на 'pending'
            'total_price' => $tour->price * $request->number_of_people,
        ]);

        // НЕ списываем места! Админ подтвердит позже

        return redirect()->route('bookings.show', $booking->booking_id)
            ->with('success', 'Заявка на бронирование отправлена! Ожидайте подтверждения администратора.');
    }

    public function show($id)
    {
        $booking = Booking::with(['tour.hotel', 'client'])
            ->where('client_id', Auth::id())
            ->findOrFail($id);

        return view('bookings.show', compact('booking'));
    }

    public function index(Request $request)
    {
        $status = $request->get('status');
        
        $query = Booking::with(['tour.hotel'])
            ->where('client_id', Auth::id())
            ->orderBy('booking_date', 'desc');

        if ($status && in_array($status, ['pending', 'confirmed', 'cancelled', 'completed'])) {
            $query->where('status', $status);
        }

        $bookings = $query->paginate(10);
        $bookingStats = $this->getBookingStats();

        return view('bookings.index', compact('bookings', 'bookingStats'));
    }

    public function cancel($id)
    {
        $booking = Booking::with('tour')
            ->where('client_id', Auth::id())
            ->whereIn('status', ['pending', 'confirmed'])  // Можно отменить и ожидающие, и подтверждённые
            ->findOrFail($id);

        // Если бронь была подтверждена - возвращаем места
        if ($booking->status === 'confirmed') {
            $booking->tour->increment('available_spots', $booking->number_of_people);
        }

        // Отменяем бронирование
        $booking->update([
            'status' => 'cancelled'
        ]);

        return redirect()->route('bookings.index')
            ->with('success', 'Бронирование успешно отменено.');
    }

    private function getBookingStats()
{
    $clientId = Auth::id();
    
    return [
        'total' => Booking::where('client_id', $clientId)->count(),
        'pending' => Booking::where('client_id', $clientId)->where('status', 'pending')->count(),
        'confirmed' => Booking::where('client_id', $clientId)->where('status', 'confirmed')->count(),
        'completed' => Booking::where('client_id', $clientId)->where('status', 'completed')->count(),
        'cancelled' => Booking::where('client_id', $clientId)->where('status', 'cancelled')->count(),
    ];
}
}