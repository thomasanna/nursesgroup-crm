<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterClientUnitUnamePwd extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         Schema::table('client_units', function (Blueprint $table) {
            $table->dropColumn(['username','password']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
         Schema::table('client_units', function (Blueprint $table) {
            $table->string('username',50)->unique();
            $table->string('password',100);
        });
    }
}
