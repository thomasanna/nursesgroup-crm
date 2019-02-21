<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterApplicantsTableVersionMay2 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('applicants', function (Blueprint $table) {
            $table->tinyInteger('referenceProgress')->after('bandId')->default(1)->comment("1:To Be Started,2:In Progress,3:Completed");
            $table->tinyInteger('rtwProgress')->after('referenceProgress')->default(1)->comment("1:To Be Started,2:In Progress,3:Completed");
            $table->tinyInteger('dbsProgress')->after('rtwProgress')->default(1)->comment("1:To Be Started,2:In Progress,3:Completed");
            $table->tinyInteger('trainingProgress')->after('dbsProgress')->default(1)->comment("1:To Be Started,2:In Progress,3:Completed");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('applicants', function (Blueprint $table) {
            $table->dropColumn(['referenceProgress','rtwProgress','dbsProgress','trainingProgress']);
        });
    }
}
