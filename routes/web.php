<?php
// routes/web.php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\TourController;
use App\Http\Controllers\HotelController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminController;

// Публичные маршруты
Route::get('/', [HomeController::class, 'index'])->name('home');

// Аутентификация
Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

// Выход (доступен для авторизованных)
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Защищенные маршруты
Route::middleware('auth')->group(function () {
    // Профиль
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('/profile/change-password', [ProfileController::class, 'showChangePasswordForm'])->name('profile.change-password');
    Route::post('/profile/change-password', [ProfileController::class, 'changePassword']);

    // Бронирования
    Route::get('/bookings', [BookingController::class, 'index'])->name('bookings.index');
    Route::post('/bookings', [BookingController::class, 'store'])->name('bookings.store');
    Route::get('/bookings/{id}', [BookingController::class, 'show'])->name('bookings.show');
    Route::post('/bookings/{id}/cancel', [BookingController::class, 'cancel'])->name('bookings.cancel');

    // Отзывы
    Route::post('/reviews', [ReviewController::class, 'store'])->name('reviews.store');
    Route::put('/reviews/{id}', [ReviewController::class, 'update'])->name('reviews.update');
    Route::delete('/reviews/{id}', [ReviewController::class, 'destroy'])->name('reviews.destroy');
});

// Публичные маршруты (доступны всем)
Route::get('/tours', [TourController::class, 'index'])->name('tours.index');
Route::get('/tours/search', [TourController::class, 'search'])->name('tours.search');
Route::get('/tours/{id}', [TourController::class, 'show'])->name('tours.show');

Route::get('/hotels', [HotelController::class, 'index'])->name('hotels.index');
Route::get('/hotels/{id}', [HotelController::class, 'show'])->name('hotels.show');

Route::get('/reviews', [ReviewController::class, 'index'])->name('reviews.index');

// Admin routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    
    // Users
    Route::get('/users', [AdminController::class, 'users'])->name('users');
    Route::get('/users/{id}', [AdminController::class, 'userShow'])->name('users.show');
    Route::get('/users/{id}/edit', [AdminController::class, 'userEdit'])->name('users.edit');
    Route::put('/users/{id}', [AdminController::class, 'userUpdate'])->name('users.update');
    Route::post('/users/{id}/block', [AdminController::class, 'userBlock'])->name('users.block');
    Route::post('/users/{id}/unblock', [AdminController::class, 'userUnblock'])->name('users.unblock');
    Route::delete('/users/{id}', [AdminController::class, 'userDelete'])->name('users.delete');
    
    // Tours (без toggle-active)
    Route::get('/tours', [AdminController::class, 'tours'])->name('tours');
    Route::get('/tours/create', [AdminController::class, 'tourCreate'])->name('tours.create');
    Route::post('/tours', [AdminController::class, 'tourStore'])->name('tours.store');
    Route::get('/tours/{id}/edit', [AdminController::class, 'tourEdit'])->name('tours.edit');
    Route::put('/tours/{id}', [AdminController::class, 'tourUpdate'])->name('tours.update');
    Route::delete('/tours/{id}', [AdminController::class, 'tourDelete'])->name('tours.delete');
    
    // Hotels (без toggle-active)
    Route::get('/hotels', [AdminController::class, 'hotels'])->name('hotels');
    Route::get('/hotels/create', [AdminController::class, 'hotelCreate'])->name('hotels.create');
    Route::post('/hotels', [AdminController::class, 'hotelStore'])->name('hotels.store');
    Route::get('/hotels/{id}/edit', [AdminController::class, 'hotelEdit'])->name('hotels.edit');
    Route::put('/hotels/{id}', [AdminController::class, 'hotelUpdate'])->name('hotels.update');
    Route::delete('/hotels/{id}', [AdminController::class, 'hotelDelete'])->name('hotels.delete');
    
    // Bookings
    Route::get('/bookings', [AdminController::class, 'bookings'])->name('bookings');
    Route::get('/bookings/{id}', [AdminController::class, 'bookingShow'])->name('bookings.show');
    Route::put('/bookings/{id}/status', [AdminController::class, 'bookingUpdateStatus'])->name('bookings.status');
    Route::post('/bookings/{id}/cancel', [AdminController::class, 'bookingCancel'])->name('bookings.cancel');
    Route::delete('/bookings/{id}', [AdminController::class, 'bookingDelete'])->name('bookings.delete');
    
    // Reviews
    Route::get('/reviews', [AdminController::class, 'reviews'])->name('reviews');
    Route::get('/reviews/{id}', [AdminController::class, 'reviewShow'])->name('reviews.show');
    Route::post('/reviews/{id}/moderate', [AdminController::class, 'reviewModerate'])->name('reviews.moderate');
    Route::delete('/reviews/{id}', [AdminController::class, 'reviewDelete'])->name('reviews.delete');
    
    // Logs
    Route::get('/logs', [AdminController::class, 'logs'])->name('logs');
});

