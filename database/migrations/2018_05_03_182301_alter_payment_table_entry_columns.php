<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterPaymentTableEntryColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->integer('paymentEntryBy')->unsigned()->nullable()->after('approvedOn');
            $table->timestamp('paymentEntryOn')->nullable()->after('paymentEntryBy');
            $table->integer('verifyPaymentBy')->unsigned()->nullable()->after('paymentEntryOn');
            $table->timestamp('verifyPaymentOn')->nullable()->after('verifyPaymentBy');
            $table->tinyInteger('modeOfPayment')->default(0)->comment('1:BACS|2:CHEQUE|3:CASH')->after('verifyPaymentOn');
            $table->string('payedFrom')->nullable()->after('modeOfPayment');
            $table->string('transctionBy')->nullable()->after('payedFrom');
            $table->date('transctionDate')->nullable()->after('transctionBy');
            $table->string('transctionReff')->nullable()->after('transctionDate');
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
            $table->dropColumn('paymentEntryBy');
            $table->dropColumn('paymentEntryOn');
            $table->dropColumn('verifyPaymentBy');
            $table->dropColumn('verifyPaymentOn');
            $table->dropColumn('modeOfPayment');
            $table->dropColumn('payedFrom');
            $table->dropColumn('transctionBy');
            $table->dropColumn('transctionDate');
            $table->dropColumn('transctionReff');
        });
    }
}
