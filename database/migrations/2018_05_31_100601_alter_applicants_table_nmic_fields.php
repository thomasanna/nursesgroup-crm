<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterApplicantsTableNmicFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('applicants', function (Blueprint $table) {
            $table->date('nmcPinExpiryDate')->nullable()->after('nmcPinNumber');
            $table->date('nmcPinReValidationDate')->nullable()->after('nmcPinExpiryDate');
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
            $table->dropColumn(['nmcPinExpiryDate','nmcPinReValidationDate']);
        });
    }
}
