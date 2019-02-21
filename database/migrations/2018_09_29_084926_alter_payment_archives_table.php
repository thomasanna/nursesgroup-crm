<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterPaymentArchivesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('payment_archives', function (Blueprint $table) {
            $table->date('paymentDate')->after('raStatus')->nullable();
            $table->unsignedInteger('bankId')->after('paymentDate')->nullable();
            $table->string('transactionNumber')->after('bankId')->nullable();
            $table->unsignedInteger('handledBy')->after('transactionNumber')->nullable();
            $table->timestamp('recordPaymentTime')->after('handledBy')->nullable();
            $table->boolean('isPaymentRecorded')->after('recordPaymentTime')->default(0)->comment("1:True,0:False");
            $table->boolean('isEmailSent')->after('isPaymentRecorded')->default(0)->comment("1:True,0:False");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('payment_archives', function (Blueprint $table) {
            $table->dropColumn([
                'paymentDate',
                'bankId',
                'transactionNumber',
                'handledBy',
                'recordPaymentTime',
                'isPaymentRecorded',
                'isEmailSent',
            ]);
        });
    }
}
