<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUsernamePasswordToClientUnitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('client_units', function (Blueprint $table) {


            $table->string('password',100)->after('branchId');
            $table->string('username',50)->after('branchId');
          
            //
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
            //
        });
    }
}
