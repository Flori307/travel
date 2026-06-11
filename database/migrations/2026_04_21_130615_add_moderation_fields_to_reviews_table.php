<?php
// database/migrations/2025_01_01_000002_add_moderation_fields_to_reviews_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddModerationFieldsToReviewsTable extends Migration
{
    public function up()
    {
        Schema::table('reviews', function (Blueprint $table) {
            // Статус модерации
            $table->enum('moderation_status', ['pending', 'approved', 'rejected'])->default('pending')->after('comment');
            
            // Комментарий модератора
            $table->text('moderation_comment')->nullable()->after('moderation_status');
            
            // Кто модерировал
            $table->unsignedBigInteger('moderated_by')->nullable()->after('moderation_comment');
            
            // Дата модерации
            $table->timestamp('moderated_at')->nullable()->after('moderated_by');
            
            // Обнаружены ли маты
            $table->boolean('has_profanity')->default(false)->after('moderated_at');
            
            // Оригинальный текст (до фильтрации)
            $table->text('original_comment')->nullable()->after('has_profanity');
            
            // Внешние ключи
            $table->foreign('moderated_by')->references('client_id')->on('clients')->onDelete('set null');
            
            // Индексы
            $table->index('moderation_status');
            $table->index('has_profanity');
            $table->index('created_at');
        });
    }

    public function down()
    {
        Schema::table('reviews', function (Blueprint $table) {
            $table->dropForeign(['moderated_by']);
            $table->dropColumn([
                'moderation_status',
                'moderation_comment',
                'moderated_by',
                'moderated_at',
                'has_profanity',
                'original_comment'
            ]);
        });
    }
}