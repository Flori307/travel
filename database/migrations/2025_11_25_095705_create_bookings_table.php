<?php
// database/migrations/2024_01_01_000004_create_bookings_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id('booking_id');
            $table->foreignId('client_id')->constrained('clients', 'client_id');
            $table->foreignId('tour_id')->constrained('tours', 'tour_id');
            $table->dateTime('booking_date');
            $table->integer('number_of_people');
            $table->enum('status', ['pending', 'confirmed', 'cancelled', 'completed'])->default('pending');
            $table->decimal('total_price', 10, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};