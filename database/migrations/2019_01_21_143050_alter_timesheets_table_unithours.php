<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTimesheetsTableUnithours extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::table('timesheets', function (Blueprint $table) {
          $table->double('staffHours', 15,2)->nullable()->after('breakHours');
          $table->double('unitHours', 15,2)->nullable()->after('staffHours');
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      Schema::table('timesheets', function (Blueprint $table) {
        $table->dropColumn(['staffHours','unitHours']);
      });
    }
}
