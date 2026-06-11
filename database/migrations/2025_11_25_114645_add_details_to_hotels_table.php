<?php
// database/migrations/2024_01_01_000009_add_details_to_hotels_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('hotels', function (Blueprint $table) {
            $table->text('description')->after('name');
            $table->text('amenities')->after('description')->nullable();
            $table->string('check_in_time')->after('amenities')->default('14:00');
            $table->string('check_out_time')->after('check_in_time')->default('12:00');
            $table->integer('room_count')->after('check_out_time')->nullable();
            $table->decimal('latitude', 10, 8)->after('room_count')->nullable();
            $table->decimal('longitude', 11, 8)->after('latitude')->nullable();
            $table->string('website')->after('longitude')->nullable();
            $table->string('email')->after('website')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('hotels', function (Blueprint $table) {
            $table->dropColumn([
                'description',
                'amenities',
                'check_in_time',
                'check_out_time',
                'room_count',
                'latitude',
                'longitude',
                'website',
                'email'
            ]);
        });
    }
};