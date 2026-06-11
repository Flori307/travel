<?php
// database/migrations/2025_01_01_000004_add_created_by_to_hotels_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCreatedByToHotelsTable extends Migration
{
    public function up()
    {
        Schema::table('hotels', function (Blueprint $table) {
            // Кто создал/обновил отель
            $table->unsignedBigInteger('created_by')->nullable()->after('email');
            $table->unsignedBigInteger('updated_by')->nullable()->after('created_by');
            
            // Статус активности отеля
            $table->boolean('is_active')->default(true)->after('updated_by');
            
            // Внешние ключи
            $table->foreign('created_by')->references('client_id')->on('clients')->onDelete('set null');
            $table->foreign('updated_by')->references('client_id')->on('clients')->onDelete('set null');
            
            // Индексы
            $table->index('is_active');
            $table->index('star_rating');
            $table->index('city');
            $table->index('country');
        });
    }

    public function down()
    {
        Schema::table('hotels', function (Blueprint $table) {
            $table->dropForeign(['created_by']);
            $table->dropForeign(['updated_by']);
            $table->dropColumn([
                'created_by',
                'updated_by',
                'is_active'
            ]);
        });
    }
}