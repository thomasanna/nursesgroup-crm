<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterStaffRightToWorkTableApril25 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('staff_right_to_works', function (Blueprint $table) {
          $table->dropColumn([
            'nokForName',
            'nokSurName',
            'nokRelationship',
            'nokAddress',
            'nokPostCode',
            'nokPhone',
            'nokMobile',
            'nokEmail',
          ]);
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
          $table->string('nokForName')->nullable()->after('passportDocumentFile');
          $table->string('nokSurName')->nullable()->after('nokForName');
          $table->string('nokRelationship')->nullable()->after('nokSurName');
          $table->text('nokAddress')->nullable()->after('nokRelationship');
          $table->string('nokPostCode')->nullable()->after('nokAddress');
          $table->string('nokPhone')->nullable()->after('nokPostCode');
          $table->string('nokMobile')->nullable()->after('nokPhone');
          $table->string('nokEmail')->nullable()->after('nokMobile');
        });
    }
}
