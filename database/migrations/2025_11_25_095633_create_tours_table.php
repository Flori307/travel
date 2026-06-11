<?php
// database/migrations/2024_01_01_000003_create_tours_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tours', function (Blueprint $table) {
            $table->id('tour_id');
            $table->foreignId('hotel_id')->constrained('hotels', 'hotel_id');
            $table->string('title', 200);
            $table->text('description');
            $table->string('destination_country', 100);
            $table->string('destination_city', 100);
            $table->date('start_date');
            $table->date('end_date');
            $table->decimal('price', 10, 2);
            $table->integer('available_spots');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tours');
    }
};