<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInvoiceArchivesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoice_archives', function (Blueprint $table) {
            $table->increments('archiveId');
            $table->unsignedInteger('invoiceId');
            $table->tinyInteger('invoiceStatus')->comment('1:Generated,0:Not Generated')->default(0);
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

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('invoice_archives');
    }
}
