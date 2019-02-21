<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterApplicantReferenceTableApril25 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('applicant_references', function (Blueprint $table) {
          $table->renameColumn('forName','fullName');
          $table->renameColumn('onStatusChanged','followUpDate');
          $table->dropColumn('surName');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('applicant_references', function (Blueprint $table) {
          $table->renameColumn('fullName','forName');
          $table->renameColumn('followUpDate','onStatusChanged');
          $table->string('surName')->nullable();
        });
    }
}
