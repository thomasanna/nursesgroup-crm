<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AdminLogs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
           Schema::create('admin_logs', function (Blueprint $table) {
            $table->increments('adminLogId');
            $table->unsignedInteger('adminId');
            $table->string('content');
            $table->unsignedInteger('author');
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
        //
    }
}
