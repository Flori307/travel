<?php
// database/migrations/2024_01_01_000002_create_hotels_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hotels', function (Blueprint $table) {
            $table->id('hotel_id');
            $table->string('name', 200);
            $table->text('address');
            $table->string('city', 100);
            $table->string('country', 100);
            $table->integer('star_rating')->checkBetween([1, 5]);
            $table->string('contact_phone', 20);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hotels');
    }
};