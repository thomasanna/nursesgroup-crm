<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTransportTablePaymentColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('transportations', function (Blueprint $table) {
          $table->date('paymentDate')->after('aggreedAmount')->nullable();
          $table->unsignedInteger('bankId')->after('paymentDate')->nullable();
          $table->string('transactionNumber')->after('bankId')->nullable();
          $table->unsignedInteger('handledBy')->after('transactionNumber')->nullable();
          $table->timestamp('recordPaymentTime')->after('handledBy')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('transportations', function (Blueprint $table) {
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
