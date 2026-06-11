<?php
// database/migrations/xxxx_xx_xx_xxxxxx_create_reviews_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReviewsTable extends Migration
{
    public function up()
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->id('review_id');
            $table->foreignId('client_id')->constrained('clients', 'client_id')->onDelete('cascade');
            $table->foreignId('tour_id')->constrained('tours', 'tour_id')->onDelete('cascade');
            $table->tinyInteger('rating')->unsigned()->between(1, 5);
            $table->text('comment');
            $table->timestamps(); // используем timestamps вместо created_at
            
            $table->unique(['client_id', 'tour_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('reviews');
    }
}