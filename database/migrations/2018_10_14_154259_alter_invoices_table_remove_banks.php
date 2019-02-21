<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterInvoicesTableRemoveBanks extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
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

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('invoices', function (Blueprint $table) {
          $table->date('paymentDate')->nullable();
          $table->unsignedInteger('bankId')->nullable();
          $table->string('transactionNumber')->nullable();
          $table->unsignedInteger('handledBy')->nullable();
          $table->timestamp('recordPaymentTime')->nullable();
          $table->boolean('isPaymentRecorded')->default(0)->comment("1:True,0:False");
          $table->boolean('isEmailSent')->default(0)->comment("1:True,0:False");
          $table->timestamps();
        });
    }
}
