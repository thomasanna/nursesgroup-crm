<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransportRecordedPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transport_recorded_payments', function (Blueprint $table) {
            $table->increments('tripPaymentRecordId');
            $table->unsignedInteger('completedTransId');
            $table->foreign('completedTransId')->references('completedTransId')->on('transport_completes');
            $table->date('paymentDate')->nullable();
            $table->unsignedInteger('bankId')->nullable();
            $table->string('transactionNumber')->nullable();
            $table->unsignedInteger('handledBy')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transport_recorded_payments');
    }
}
