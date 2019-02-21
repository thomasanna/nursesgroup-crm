<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterInvoicesTablePayments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->date('paymentDate')->after('approvedOn')->nullable();
            $table->unsignedInteger('bankId')->after('paymentDate')->nullable();
            $table->string('transactionNumber')->after('bankId')->nullable();
            $table->unsignedInteger('handledBy')->after('transactionNumber')->nullable();
            $table->timestamp('recordPaymentTime')->after('handledBy')->nullable();
            $table->boolean('isPaymentRecorded')->after('recordPaymentTime')->nullable()->comment('1:True,0:False');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropColumn([
                'paymentDate',
                'bankId',
                'transactionNumber',
                'handledBy',
                'recordPaymentTime',
                'isPaymentRecorded',
            ]);
        });
    }
}
