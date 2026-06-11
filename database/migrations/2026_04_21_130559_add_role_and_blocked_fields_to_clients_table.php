<?php
// database/migrations/2025_01_01_000001_add_role_and_blocked_fields_to_clients_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRoleAndBlockedFieldsToClientsTable extends Migration
{
    public function up()
    {
        Schema::table('clients', function (Blueprint $table) {
            // Роли пользователей
            $table->enum('role', ['user', 'moderator', 'admin'])->default('user')->after('password');
            
            // Блокировка пользователя
            $table->boolean('is_blocked')->default(false)->after('role');
            $table->timestamp('blocked_at')->nullable()->after('is_blocked');
            $table->text('block_reason')->nullable()->after('blocked_at');
            
            // Кто заблокировал
            $table->unsignedBigInteger('blocked_by')->nullable()->after('block_reason');
            
            // Внешний ключ для blocked_by
            $table->foreign('blocked_by')->references('client_id')->on('clients')->onDelete('set null');
            
            // Индексы для быстрого поиска
            $table->index('role');
            $table->index('is_blocked');
            $table->index('email');
        });
    }

    public function down()
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->dropForeign(['blocked_by']);
            $table->dropColumn([
                'role', 
                'is_blocked', 
                'blocked_at', 
                'block_reason', 
                'blocked_by'
            ]);
        });
    }
}