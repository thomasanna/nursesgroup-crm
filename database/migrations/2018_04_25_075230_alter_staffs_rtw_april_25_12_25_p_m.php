<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterStaffsRtwApril251225PM extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('staff_right_to_works', function (Blueprint $table) {
          $table->string('passportDateOfIssue')->default('2000-01-01')->change();
          $table->string('passportExpiryDate')->default('2000-01-01')->change();
          $table->string('visaDateOfIssue')->default('2000-01-01')->change();
          $table->string('visaExpiryDate')->default('2018-01-01')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('staff_right_to_works', function (Blueprint $table) {
          $table->string('passportDateOfIssue')->default(NULL)->nullable()->change();
          $table->string('passportExpiryDate')->default(NULL)->nullable()->change();
          $table->string('visaDateOfIssue')->default(NULL)->nullable()->change();
          $table->string('visaExpiryDate')->default(NULL)->nullable()->change();
        });
    }
}
