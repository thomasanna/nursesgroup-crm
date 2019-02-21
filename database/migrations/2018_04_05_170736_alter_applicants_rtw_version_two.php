<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterApplicantsRtwVersionTwo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('applicant_right_to_works', function (Blueprint $table) {
            $table->dropColumn(['rightToWorkWithoutPermission','medicalCondition']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('applicant_right_to_works', function (Blueprint $table) {
            $table->tinyInteger('rightToWorkWithoutPermission')->nullable();
            $table->tinyInteger('medicalCondition')->nullable()->comment('1:Yes,2:No');
        });
    }
}
