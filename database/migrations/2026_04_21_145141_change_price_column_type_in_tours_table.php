<?php
// database/migrations/2025_01_01_000017_change_price_column_type_in_tours_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangePriceColumnTypeInToursTable extends Migration
{
    public function up()
    {
        Schema::table('tours', function (Blueprint $table) {
            $table->decimal('price', 12, 2)->change(); // 12 цифр всего, 2 после запятой
        });
    }

    public function down()
    {
        Schema::table('tours', function (Blueprint $table) {
            $table->decimal('price', 8, 2)->change();
        });
    }
}