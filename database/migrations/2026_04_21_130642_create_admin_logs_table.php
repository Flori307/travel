<?php
// database/migrations/2025_01_01_000005_create_admin_logs_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdminLogsTable extends Migration
{
    public function up()
    {
        Schema::create('admin_logs', function (Blueprint $table) {
            $table->id('log_id');
            
            // Кто совершил действие
            $table->unsignedBigInteger('admin_id');
            
            // Тип действия (user, tour, hotel, review, booking)
            $table->string('action_type', 50);
            
            // Само действие (create, update, delete, block, unblock, moderate)
            $table->string('action', 50);
            
            // ID объекта, над которым совершено действие
            $table->unsignedBigInteger('target_id')->nullable();
            
            // Старое значение (JSON)
            $table->json('old_values')->nullable();
            
            // Новое значение (JSON)
            $table->json('new_values')->nullable();
            
            // IP адрес
            $table->string('ip_address', 45)->nullable();
            
            // User Agent
            $table->text('user_agent')->nullable();
            
            // Дополнительная информация
            $table->text('additional_info')->nullable();
            
            $table->timestamps();
            
            // Внешние ключи
            $table->foreign('admin_id')->references('client_id')->on('clients')->onDelete('cascade');
            
            // Индексы для быстрого поиска
            $table->index('action_type');
            $table->index('action');
            $table->index('target_id');
            $table->index('created_at');
            $table->index(['admin_id', 'created_at']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('admin_logs');
    }
}