<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterCompltedTransportsSattusColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('transport_completes', function (Blueprint $table) {
            $table->boolean('proceedToPay')->after('payeeWeek')->default(0)->comment('1:Yes,0:No');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('transport_completes', function (Blueprint $table) {
            $table->dropColumn(['proceedToPay']);
        });
    }
}
