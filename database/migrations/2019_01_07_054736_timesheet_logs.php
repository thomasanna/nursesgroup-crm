<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TimesheetLogs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('timesheet_logs', function (Blueprint $table) {
                $table->increments('timesheetLogId'); 
                $table->unsignedInteger('timesheetId');
                $table->string('content');
                $table->unsignedInteger('author')->nullable();
                $table->tinyInteger('type')->comment('1:Logicaly,2:Manually')->default(1);
                $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
         Schema::drop('timesheet_logs');
    }
}
