<?php
// database/migrations/2025_01_01_000003_add_created_by_to_tours_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCreatedByToToursTable extends Migration
{
    public function up()
    {
        Schema::table('tours', function (Blueprint $table) {
            // Кто создал/обновил тур
            $table->unsignedBigInteger('created_by')->nullable()->after('image_url');
            $table->unsignedBigInteger('updated_by')->nullable()->after('created_by');
            
            // Статус активности тура
            $table->boolean('is_active')->default(true)->after('updated_by');
            
            // Причина деактивации
            $table->text('deactivation_reason')->nullable()->after('is_active');
            
            // Внешние ключи
            $table->foreign('created_by')->references('client_id')->on('clients')->onDelete('set null');
            $table->foreign('updated_by')->references('client_id')->on('clients')->onDelete('set null');
            
            // Индексы
            $table->index('is_active');
            $table->index('start_date');
            $table->index('price');
        });
    }

    public function down()
    {
        Schema::table('tours', function (Blueprint $table) {
            $table->dropForeign(['created_by']);
            $table->dropForeign(['updated_by']);
            $table->dropColumn([
                'created_by',
                'updated_by',
                'is_active',
                'deactivation_reason'
            ]);
        });
    }
}