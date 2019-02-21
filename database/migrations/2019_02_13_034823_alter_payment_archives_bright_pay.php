<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterPaymentArchivesBrightPay extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('payment_archives', function (Blueprint $table) {
            $table->double('tax',15,2)->after('raStatus')->nullable();
            $table->double('employeeNIC',15,2)->after('tax')->nullable();
            $table->double('employeePension',15,2)->after('employeeNIC')->nullable();
            $table->double('studentLoan',15,2)->after('employeePension')->nullable();
            $table->double('advance',15,2)->after('studentLoan')->nullable();
            $table->double('dbs',15,2)->after('advance')->nullable();
            $table->double('uniform',15,2)->after('dbs')->nullable();
            $table->double('cancellationCharge',15,2)->after('uniform')->nullable();
            $table->double('employerNIC',15,2)->after('cancellationCharge')->nullable();
            $table->double('employerPension',15,2)->after('employerNIC')->nullable();
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
                'tax',
                'employeeNIC',
                'employeePension',
                'studentLoan',
                'advance',
                'dbs',
                'uniform',
                'cancellationCharge',
                'employerNIC',
                'employerPension',
            ]);
        });
    }
}
