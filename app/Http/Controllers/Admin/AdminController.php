<?php
// app/Http/Controllers/Admin/AdminController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Tour;
use App\Models\Hotel;
use App\Models\Booking;
use App\Models\Review;
use App\Models\AdminLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AdminController extends Controller
{
    
    // Dashboard
    public function dashboard()
    {
        $stats = [
            'total_users' => Client::count(),
            'active_users' => Client::where('is_blocked', false)->count(),
            'blocked_users' => Client::where('is_blocked', true)->count(),
            'total_tours' => Tour::count(),
            'active_tours' => Tour::where('available_spots', '>', 0)->count(),
            'total_hotels' => Hotel::count(),
            'total_bookings' => Booking::count(),
            'pending_reviews' => Review::where('moderation_status', 'pending')->count(),
            'total_revenue' => Booking::where('status', 'confirmed')->sum('total_price'),
        ];
        
        $recentBookings = Booking::with(['client', 'tour'])->latest()->limit(10)->get();
        $pendingReviews = Review::with(['client', 'tour'])->where('moderation_status', 'pending')->latest()->limit(5)->get();
        $recentUsers = Client::latest()->limit(5)->get();
        
        return view('admin.dashboard', compact('stats', 'recentBookings', 'pendingReviews', 'recentUsers'));
    }
    
    // ==================== USERS MANAGEMENT ====================
    
    public function users(Request $request)
    {
        $query = Client::query();
        
        if ($request->has('search') && $request->search) {
            $query->where(function($q) use ($request) {
                $q->where('first_name', 'like', '%' . $request->search . '%')
                  ->orWhere('last_name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }
        
        if ($request->has('role') && $request->role) {
            $query->where('role', $request->role);
        }
        
        if ($request->has('status') && $request->status) {
            if ($request->status === 'blocked') {
                $query->where('is_blocked', true);
            } elseif ($request->status === 'active') {
                $query->where('is_blocked', false);
            }
        }
        
        $users = $query->orderBy('created_at', 'desc')->paginate(20);
        
        return view('admin.users.index', compact('users'));
    }
    
    public function userShow($id)
    {
        $user = Client::with(['bookings.tour', 'reviews'])->findOrFail($id);
        
        $stats = [
            'total_bookings' => $user->bookings->count(),
            'total_spent' => $user->bookings->where('status', 'confirmed')->sum('total_price'),
            'total_reviews' => $user->reviews->count(),
            'active_bookings' => $user->bookings->where('status', 'confirmed')->count(),
        ];
        
        $recentBookings = $user->bookings()->with('tour')->latest()->limit(5)->get();
        $recentReviews = $user->reviews()->with('tour')->latest()->limit(5)->get();
        
        return view('admin.users.show', compact('user', 'stats', 'recentBookings', 'recentReviews'));
    }
    
    public function userEdit($id)
    {
        $user = Client::findOrFail($id);
        return view('admin.users.edit', compact('user'));
    }
    
    public function userUpdate(Request $request, $id)
    {
        $user = Client::findOrFail($id);
        $oldData = $user->toArray();
        
        $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'email' => 'required|email|unique:clients,email,' . $id . ',client_id',
            'phone' => 'required|string|max:20',
            'role' => 'required|in:user,moderator,admin',
        ]);
        
        $user->update($request->only(['first_name', 'last_name', 'email', 'phone', 'role']));
        
        $passwordChanged = false;
        if ($request->filled('password')) {
            $request->validate(['password' => 'min:8|confirmed']);
            $user->update(['password' => Hash::make($request->password)]);
            $passwordChanged = true;
        }
        
        // Логируем обновление
        AdminLog::log(
            auth()->id(),
            'user',
            'update',
            $user->client_id,
            $oldData,
            $user->toArray(),
            'Пользователь обновлен. Изменены поля: ' . implode(', ', array_keys(array_diff_assoc($user->toArray(), $oldData))) . ($passwordChanged ? ', пароль изменен' : '')
        );
        
        return redirect()->route('admin.users.show', $user->client_id)->with('success', 'Пользователь успешно обновлен');
    }
    
    public function userBlock(Request $request, $id)
    {
        $user = Client::findOrFail($id);
        
        if ($user->role === 'admin') {
            return redirect()->back()->with('error', 'Нельзя заблокировать администратора');
        }
        
        if ($user->client_id == auth()->id()) {
            return redirect()->back()->with('error', 'Нельзя заблокировать самого себя');
        }
        
        $request->validate([
            'block_reason' => 'required|string|max:500'
        ]);
        
        $user->block($request->block_reason, auth()->id());
        
        // Отменяем все активные бронирования пользователя
        $activeBookings = $user->bookings()->whereIn('status', ['pending', 'confirmed'])->get();
        foreach ($activeBookings as $booking) {
            if ($booking->status === 'confirmed') {
                $booking->tour->increment('available_spots', $booking->number_of_people);
            }
            $booking->status = 'cancelled';
            $booking->save();
        }
        
        // Логируем блокировку
        AdminLog::log(
            auth()->id(),
            'user',
            'block',
            $user->client_id,
            ['is_blocked' => false, 'block_reason' => null],
            ['is_blocked' => true, 'block_reason' => $request->block_reason],
            'Пользователь заблокирован. Причина: ' . $request->block_reason
        );
        
        return redirect()->back()->with('success', 'Пользователь "' . $user->full_name . '" заблокирован. Причина: ' . $request->block_reason);
    }

    public function userUnblock($id)
    {
        $user = Client::findOrFail($id);
        
        $oldData = ['is_blocked' => $user->is_blocked, 'block_reason' => $user->block_reason];
        
        $user->unblock();
        
        // Логируем разблокировку
        AdminLog::log(
            auth()->id(),
            'user',
            'unblock',
            $user->client_id,
            $oldData,
            ['is_blocked' => false, 'block_reason' => null],
            'Пользователь разблокирован'
        );
        
        return redirect()->back()->with('success', 'Пользователь "' . $user->full_name . '" разблокирован');
    }
    
    public function userDelete($id)
    {
        $user = Client::findOrFail($id);

        if ($user->role === 'admin') {
            return redirect()->back()->with('error', 'Нельзя удалить администратора');
        }

        if ($user->client_id == auth()->id()) {
            return redirect()->back()->with('error', 'Нельзя удалить самого себя');
        }

        try {
            DB::beginTransaction();
            
            $userData = $user->toArray();
            
            // Принудительно удаляем связанные отзывы
            $user->reviews()->forceDelete();
            
            // Отменяем активные бронирования перед удалением
            $activeBookings = $user->bookings()->whereIn('status', ['pending', 'confirmed'])->get();
            foreach ($activeBookings as $booking) {
                if ($booking->status === 'confirmed') {
                    $booking->tour->increment('available_spots', $booking->number_of_people);
                }
            }
            
            // Принудительно удаляем бронирования
            $user->bookings()->forceDelete();
            
            // Удаляем логи админа если есть
            if ($user->role === 'moderator' || $user->role === 'admin') {
                AdminLog::where('admin_id', $user->client_id)->delete();
            }
            
            // Логируем удаление
            AdminLog::log(
                auth()->id(),
                'user',
                'delete',
                $user->client_id,
                $userData,
                null,
                'Пользователь полностью удален из системы'
            );
            
            // Принудительно удаляем пользователя
            $user->forceDelete();
            
            DB::commit();
            
            return redirect()->route('admin.users')->with('success', 'Пользователь и все связанные данные полностью удалены из базы данных');
            
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Ошибка при удалении: ' . $e->getMessage());
        }
    }
    
    // ==================== TOURS MANAGEMENT ====================
    
    public function tours(Request $request)
    {
        $query = Tour::with(['hotel']);
        
        if ($request->has('search') && $request->search) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }
        
        $tours = $query->orderBy('created_at', 'desc')->paginate(15);
        $hotels = Hotel::all();
        
        return view('admin.tours.index', compact('tours', 'hotels'));
    }
    
    public function tourCreate()
    {
        $hotels = Hotel::all();
        return view('admin.tours.create', compact('hotels'));
    }
    
    public function tourStore(Request $request)
    {
        $request->validate([
            'hotel_id' => 'required|exists:hotels,hotel_id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'destination_country' => 'required|string|max:100',
            'destination_city' => 'required|string|max:100',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'price' => 'required|numeric|min:0',
            'available_spots' => 'required|integer|min:0',
            'image_url' => 'required|url',
        ]);
        
        $data = $request->all();
        $data['created_by'] = auth()->id();
        $data['updated_by'] = auth()->id();
        
        $tour = Tour::create($data);
        
        // Логируем создание
        AdminLog::log(
            auth()->id(),
            'tour',
            'create',
            $tour->tour_id,
            null,
            $tour->toArray(),
            'Создан новый тур: ' . $tour->title
        );
        
        return redirect()->route('admin.tours')->with('success', 'Тур создан успешно');
    }
    
    public function tourEdit($id)
    {
        $tour = Tour::findOrFail($id);
        $hotels = Hotel::all();
        return view('admin.tours.edit', compact('tour', 'hotels'));
    }
    
    public function tourUpdate(Request $request, $id)
    {
        $tour = Tour::findOrFail($id);
        $oldData = $tour->toArray();
        
        $request->validate([
            'hotel_id' => 'required|exists:hotels,hotel_id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'destination_country' => 'required|string|max:100',
            'destination_city' => 'required|string|max:100',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'price' => 'required|numeric|min:0',
            'available_spots' => 'required|integer|min:0',
            'image_url' => 'required|url',
        ]);
        
        $tour->update($request->all());
        
        // Логируем обновление
        AdminLog::log(
            auth()->id(),
            'tour',
            'update',
            $tour->tour_id,
            $oldData,
            $tour->toArray(),
            'Обновлен тур: ' . $tour->title
        );
        
        return redirect()->route('admin.tours')->with('success', 'Тур обновлен успешно');
    }
    
    public function tourDelete($id)
    {
        $tour = Tour::findOrFail($id);
        
        try {
            DB::beginTransaction();
            
            $tourData = $tour->toArray();
            
            // Отменяем активные бронирования и возвращаем места
            $activeBookings = $tour->bookings()->whereIn('status', ['confirmed', 'pending'])->get();
            foreach ($activeBookings as $booking) {
                $booking->status = 'cancelled';
                $booking->save();
            }
            
            // Удаляем связанные отзывы
            $tour->reviews()->delete();
            
            // Удаляем бронирования
            $tour->bookings()->delete();
            
            // Логируем удаление
            AdminLog::log(
                auth()->id(),
                'tour',
                'delete',
                $tour->tour_id,
                $tourData,
                null,
                'Удален тур: ' . $tour->title
            );
            
            // Удаляем тур
            $tour->delete();
            
            DB::commit();
            
            return redirect()->route('admin.tours')->with('success', 'Тур и все связанные данные удалены');
            
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Ошибка при удалении: ' . $e->getMessage());
        }
    }
    
    // ==================== HOTELS MANAGEMENT ====================
    
    public function hotels(Request $request)
    {
        $query = Hotel::query();
        
        if ($request->has('search') && $request->search) {
            $query->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('city', 'like', '%' . $request->search . '%');
        }
        
        $hotels = $query->orderBy('name')->paginate(15);
        
        return view('admin.hotels.index', compact('hotels'));
    }
    
    public function hotelCreate()
    {
        return view('admin.hotels.create');
    }
    
    public function hotelStore(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'description' => 'required|string',
        'address' => 'required|string',
        'city' => 'required|string|max:100',
        'country' => 'required|string|max:100',
        'star_rating' => 'required|integer|min:1|max:5',
        'contact_phone' => 'required|string|max:20',
        'email' => 'required|email',
        'image_url' => 'required|url',
        'room_count' => 'required|integer|min:1',
        'check_in_time' => 'required|string',
        'check_out_time' => 'required|string',
        'website' => 'nullable|url',
    ]);
    
    $data = $request->all();
    $data['amenities'] = json_encode($request->amenities ?? []);
    $data['is_active'] = true;
    
    // НЕ ДОБАВЛЯЕМ created_by и updated_by
    
    $hotel = Hotel::create($data);
    
    // Логируем создание (этого достаточно для аудита)
    AdminLog::log(
        auth()->id(),
        'hotel',
        'create',
        $hotel->hotel_id,
        null,
        $hotel->toArray(),
        'Создан новый отель: ' . $hotel->name
    );
    
    return redirect()->route('admin.hotels')->with('success', 'Отель создан успешно');
}
    
    public function hotelEdit($id)
    {
        $hotel = Hotel::findOrFail($id);
        $amenities = json_decode($hotel->amenities, true) ?? [];
        return view('admin.hotels.edit', compact('hotel', 'amenities'));
    }
    
    public function hotelUpdate(Request $request, $id)
{
    $hotel = Hotel::findOrFail($id);
    $oldData = $hotel->toArray();
    
    $request->validate([
        'name' => 'required|string|max:255',
        'description' => 'required|string',
        'address' => 'required|string',
        'city' => 'required|string|max:100',
        'country' => 'required|string|max:100',
        'star_rating' => 'required|integer|min:1|max:5',
        'contact_phone' => 'required|string|max:20',
        'email' => 'required|email',
        'image_url' => 'required|url',
        'room_count' => 'required|integer|min:1',
        'check_in_time' => 'required|string',
        'check_out_time' => 'required|string',
        'website' => 'nullable|url',
    ]);
    
    $data = $request->all();
    $data['amenities'] = json_encode($request->amenities ?? []);
    // НЕ ДОБАВЛЯЕМ updated_by
    
    $hotel->update($data);
    
    AdminLog::log(
        auth()->id(),
        'hotel',
        'update',
        $hotel->hotel_id,
        $oldData,
        $hotel->toArray(),
        'Обновлен отель: ' . $hotel->name
    );
    
    return redirect()->route('admin.hotels')->with('success', 'Отель обновлен успешно');
}
    
    public function hotelDelete($id)
    {
        $hotel = Hotel::findOrFail($id);
        
        try {
            DB::beginTransaction();
            
            $hotelData = $hotel->toArray();
            
            // Удаляем все туры отеля (включая их бронирования и отзывы)
            foreach ($hotel->tours as $tour) {
                $tour->reviews()->delete();
                $tour->bookings()->delete();
                $tour->delete();
            }
            
            // Логируем удаление
            AdminLog::log(
                auth()->id(),
                'hotel',
                'delete',
                $hotel->hotel_id,
                $hotelData,
                null,
                'Удален отель: ' . $hotel->name . ' и все связанные с ним туры'
            );
            
            $hotel->delete();
            
            DB::commit();
            
            return redirect()->route('admin.hotels')->with('success', 'Отель и все связанные с ним туры удалены');
            
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Ошибка при удалении: ' . $e->getMessage());
        }
    }
    
    // ==================== BOOKINGS MANAGEMENT ====================
    
    public function bookings(Request $request)
{
    $query = Booking::with(['client', 'tour']);
    
    if ($request->has('status') && $request->status) {
        $query->where('status', $request->status);
    }
    
    if ($request->has('search') && $request->search) {
        $query->whereHas('client', function($q) use ($request) {
            $q->where('first_name', 'like', '%' . $request->search . '%')
              ->orWhere('last_name', 'like', '%' . $request->search . '%')
              ->orWhere('email', 'like', '%' . $request->search . '%');
        });
    }
    
    $bookings = $query->orderBy('created_at', 'desc')->paginate(20);
    
    $stats = [
        'total' => Booking::count(),
        'pending' => Booking::where('status', 'pending')->count(),
        'confirmed' => Booking::where('status', 'confirmed')->count(),
        'cancelled' => Booking::where('status', 'cancelled')->count(),
        'completed' => Booking::where('status', 'completed')->count(),
        'total_revenue' => Booking::where('status', 'confirmed')->sum('total_price'),
    ];
    
    return view('admin.bookings.index', compact('bookings', 'stats'));
}
    
    public function bookingShow($id)
    {
        $booking = Booking::with(['client', 'tour.hotel'])->findOrFail($id);
        return view('admin.bookings.show', compact('booking'));
    }
    
    public function bookingUpdateStatus(Request $request, $id)
{
    $booking = Booking::findOrFail($id);
    
    $request->validate([
        'status' => 'required|in:pending,confirmed,cancelled,completed'
    ]);
    
    $oldStatus = $booking->status;
    
    // При подтверждении бронирования - списываем места
    if ($request->status === 'confirmed' && $oldStatus !== 'confirmed') {
        // Проверяем, хватает ли мест
        if ($booking->tour->available_spots < $booking->number_of_people) {
            return redirect()->back()->with('error', 'Недостаточно свободных мест для подтверждения бронирования. Доступно: ' . $booking->tour->available_spots);
        }
        $booking->tour->decrement('available_spots', $booking->number_of_people);
    }
    
    // При отмене подтверждённого бронирования - возвращаем места
    if ($request->status === 'cancelled' && $oldStatus === 'confirmed') {
        $booking->tour->increment('available_spots', $booking->number_of_people);
    }
    
    // При переводе из подтверждённого в ожидание - возвращаем места
    if ($request->status === 'pending' && $oldStatus === 'confirmed') {
        $booking->tour->increment('available_spots', $booking->number_of_people);
    }
    
    $booking->update(['status' => $request->status]);
    
    // Логируем изменение статуса
    AdminLog::log(
        auth()->id(),
        'booking',
        'update',
        $booking->booking_id,
        ['status' => $oldStatus],
        ['status' => $request->status],
        'Изменен статус бронирования #' . $booking->booking_id . ' с "' . $oldStatus . '" на "' . $request->status . '"'
    );
    
    $statusMessages = [
        'pending' => 'Бронирование переведено в статус ожидания',
        'confirmed' => 'Бронирование подтверждено. Места забронированы.',
        'cancelled' => 'Бронирование отменено. Места возвращены.',
        'completed' => 'Бронирование отмечено как завершённое.'
    ];
    
    return redirect()->back()->with('success', $statusMessages[$request->status] ?? 'Статус бронирования обновлен');
}
    
    public function bookingCancel($id)
    {
        $booking = Booking::findOrFail($id);
        
        if ($booking->status === 'confirmed') {
            $booking->tour->increment('available_spots', $booking->number_of_people);
        }
        
        $oldStatus = $booking->status;
        $booking->update(['status' => 'cancelled']);
        
        // Логируем отмену
        AdminLog::log(
            auth()->id(),
            'booking',
            'cancel',
            $booking->booking_id,
            ['status' => $oldStatus],
            ['status' => 'cancelled'],
            'Отменено бронирование #' . $booking->booking_id
        );
        
        return redirect()->back()->with('success', 'Бронирование отменено');
    }
    
    public function bookingDelete($id)
    {
        $booking = Booking::findOrFail($id);
        $bookingData = $booking->toArray();
        
        // Логируем удаление
        AdminLog::log(
            auth()->id(),
            'booking',
            'delete',
            $booking->booking_id,
            $bookingData,
            null,
            'Удалено бронирование #' . $booking->booking_id . ' тура: ' . ($booking->tour->title ?? 'N/A')
        );
        
        $booking->delete();
        
        return redirect()->route('admin.bookings')->with('success', 'Бронирование удалено');
    }
    
    // ==================== REVIEWS MODERATION ====================
    
    public function reviews(Request $request)
    {
        $query = Review::with(['client', 'tour']);
        
        if ($request->has('moderation_status') && $request->moderation_status) {
            $query->where('moderation_status', $request->moderation_status);
        }
        
        if ($request->has('has_profanity') && $request->has_profanity) {
            $query->where('has_profanity', $request->has_profanity === 'yes');
        }
        
        if ($request->has('search') && $request->search) {
            $query->where('comment', 'like', '%' . $request->search . '%');
        }
        
        $reviews = $query->orderBy('created_at', 'desc')->paginate(20);
        
        $stats = [
            'total' => Review::count(),
            'pending' => Review::where('moderation_status', 'pending')->count(),
            'approved' => Review::where('moderation_status', 'approved')->count(),
            'rejected' => Review::where('moderation_status', 'rejected')->count(),
            'with_profanity' => Review::where('has_profanity', true)->count(),
        ];
        
        return view('admin.reviews.index', compact('reviews', 'stats'));
    }
    
    public function reviewShow($id)
    {
        try {
            $review = Review::with(['client', 'tour'])->findOrFail($id);
            if ($review->moderated_by) {
                $review->load(['moderator']);
            }
            return view('admin.reviews.show', compact('review'));
        } catch (\Exception $e) {
            return redirect()->route('admin.reviews')->with('error', 'Отзыв не найден');
        }
    }
    
    public function reviewModerate(Request $request, $id)
    {
        $review = Review::findOrFail($id);
        
        $request->validate([
            'moderation_status' => 'required|in:approved,rejected',
            'moderation_comment' => 'nullable|string|max:500'
        ]);
        
        $oldStatus = $review->moderation_status;
        
        $review->update([
            'moderation_status' => $request->moderation_status,
            'moderation_comment' => $request->moderation_comment,
            'moderated_by' => auth()->id(),
            'moderated_at' => now(),
        ]);
        
        // Логируем модерацию
        AdminLog::log(
            auth()->id(),
            'review',
            'moderate',
            $review->review_id,
            ['moderation_status' => $oldStatus],
            ['moderation_status' => $request->moderation_status, 'moderation_comment' => $request->moderation_comment],
            'Отзыв #' . $review->review_id . ' ' . ($request->moderation_status === 'approved' ? 'одобрен' : 'отклонен')
        );
        
        $message = $request->moderation_status === 'approved' ? 'Отзыв одобрен' : 'Отзыв отклонен';
        
        return redirect()->back()->with('success', $message);
    }
    
    public function reviewDelete($id)
    {
        $review = Review::findOrFail($id);
        $reviewData = $review->toArray();
        
        // Логируем удаление
        AdminLog::log(
            auth()->id(),
            'review',
            'delete',
            $review->review_id,
            $reviewData,
            null,
            'Удален отзыв #' . $review->review_id . ' от пользователя ' . ($review->client->full_name ?? 'N/A')
        );
        
        $review->delete();
        
        return redirect()->route('admin.reviews')->with('success', 'Отзыв удален');
    }
    
    // ==================== LOGS ====================
    
    public function logs(Request $request)
    {
        $query = AdminLog::with('admin');
        
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('ip_address', 'like', '%' . $search . '%')
                  ->orWhere('action', 'like', '%' . $search . '%')
                  ->orWhere('additional_info', 'like', '%' . $search . '%');
            });
        }
        
        if ($request->has('action_type') && $request->action_type) {
            $query->where('action_type', $request->action_type);
        }
        
        if ($request->has('action') && $request->action) {
            $query->where('action', $request->action);
        }
        
        $logs = $query->orderBy('created_at', 'desc')->paginate(30);
        
        return view('admin.logs.index', compact('logs'));
    }
}