<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterPaymentsTableRemoveold extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn([
              'paymentEntryBy',
              'paymentEntryOn',
              'verifyPaymentBy',
              'verifyPaymentOn',
              'modeOfPayment',
              'payedFrom',
              'transctionBy',
              'transctionDate',
              'transctionReff',
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
        Schema::table('payments', function (Blueprint $table) {
            //
        });
    }
}
