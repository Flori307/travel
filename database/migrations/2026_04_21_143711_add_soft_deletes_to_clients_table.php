<?php
// database/migrations/2025_01_01_000014_add_soft_deletes_to_clients_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSoftDeletesToClientsTable extends Migration
{
    public function up()
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
}