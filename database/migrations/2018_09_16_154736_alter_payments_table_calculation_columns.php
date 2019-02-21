<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterPaymentsTableCalculationColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn('amount');
            $table->double('totalHr',15,2)->after('timesheetId')->nullable();
            $table->double('totalTa',15,2)->after('totalHr')->nullable();
            $table->double('totalExtraTa',15,2)->after('totalTa')->nullable();
            $table->double('totalBonus',15,2)->after('totalExtraTa')->nullable();
            $table->double('grossPay',15,2)->after('totalBonus')->nullable();
            $table->double('weeklyPay',15,2)->after('grossPay')->nullable();
            $table->double('holidayPay',15,2)->after('weeklyPay')->nullable();
            $table->double('grossTa',15,2)->after('holidayPay')->nullable();
            $table->double('fhRate',15,2)->after('grossTa')->nullable();
            $table->text('remarks')->after('fhRate')->nullable();
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
            $table->double('amount');
            $table->dropColumn([
              'totalHr',
              'totalTa',
              'totalExtraTa',
              'totalBonus',
              'grossPay',
              'weeklyPay',
              'holidayPay',
              'grossTa',
              'fhRate',
              'remarks',
            ]);
        });
    }
}
