<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->double('totalHr',15,2)->after('timesheetId')->nullable();
            $table->double('totEnic',15,2)->after('totalHr')->nullable();
            $table->double('lineTotal',15,2)->after('totEnic')->nullable();
            
            $table->string('remarks')->after('lineTotal')->nullable();
            $table->tinyInteger('invceFrqncy')->after('remarks')->nullable();
            $table->tinyInteger('weekYear')->after('invceFrqncy')->nullable();
            $table->tinyInteger('monthNumbr')->after('weekYear')->nullable();
            $table->tinyInteger('weekNumbr')->after('monthNumbr')->nullable();

            $table->unsignedInteger('verifiedBy')->after('weekNumbr')->nullable();
            $table->date('verifiedOn')->after('verifiedBy')->nullable();
            $table->unsignedInteger('approvedBy')->after('verifiedOn')->nullable();
            $table->date('approvedOn')->after('approvedBy')->nullable();
           
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
                'totalHr',
                'totEnic',
                'lineTotal',
                'remarks',
                'invceFrqncy',
                'weekYear',
                'monthNumbr',
                'verifiedBy',
                'verifiedOn',
                'approvedBy',
                'approvedOn',
            ]);
        });
    }
}
