<?php
// database/migrations/xxxx_xx_xx_xxxxxx_add_remember_token_to_clients_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRememberTokenToClientsTable extends Migration
{
    public function up()
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->rememberToken()->after('password');
        });
    }

    public function down()
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->dropRememberToken();
        });
    }
}