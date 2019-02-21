<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterInvoicesArchivesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('invoice_archives', function (Blueprint $table) {
            $table->string('invoiceNumber')->after('invoiceStatus')->nullable();
            $table->date('invoiceDate')->after('invoiceNumber')->nullable();
        });

        Schema::table('payment_archives', function (Blueprint $table) {
            $table->string('raNumber')->after('raStatus')->nullable();
            $table->date('raDate')->after('raNumber')->nullable();
        });

        // Schema::rename('invoices', 'unit_bills');
        // Schema::rename('invoice_archives', 'invoices');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('invoice_archives', function (Blueprint $table) {
            $table->dropColumn([
                'invoiceNumber','invoiceDate'
                ]);
        });

        Schema::table('payment_archives', function (Blueprint $table) {
            $table->dropColumn([
                'raNumber','raDate'
                ]);
        });

        // Schema::rename('unit_bills', 'invoices');
        // Schema::rename('invoices', 'invoice_archives');
    }
}
