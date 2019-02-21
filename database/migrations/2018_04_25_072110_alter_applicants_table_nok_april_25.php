<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterApplicantsTableNokApril25 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('applicants', function (Blueprint $table) {
          $table->string('nokFullName')->nullable()->after('bandId');
          $table->string('nokRelationship')->nullable()->after('nokFullName');
          $table->text('nokAddress')->nullable()->after('nokRelationship');
          $table->string('nokPostCode')->nullable()->after('nokAddress');
          $table->string('nokPhone')->nullable()->after('nokPostCode');
          $table->string('nokMobile')->nullable()->after('nokPhone');
          $table->string('nokEmail')->nullable()->after('nokMobile');
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
          $table->dropColumn([
            'nokFullName',
            'nokRelationship',
            'nokAddress',
            'nokPostCode',
            'nokPhone',
            'nokMobile',
            'nokEmail',
          ]);
        });
    }
}
