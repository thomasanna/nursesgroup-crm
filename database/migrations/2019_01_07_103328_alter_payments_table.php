<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn([
                'grossPay',
                'weeklyPay',
                'holidayPay',
                'grossTa',
                'fhRate',
                'totalHr',
                'totalTa',
                'totalExtraTa',
                'totalBonus',
            ]);

            $table->double('hourlyRate',15,2)->nullable()->after('timesheetId');
            $table->double('ta',15,2)->nullable()->after('hourlyRate');
            $table->double('extraTa',15,2)->nullable()->after('ta');
            $table->double('bonus',15,2)->nullable()->after('extraTa');

            
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
            $table->dropColumn([
                'hourlyRate',
                'ta',
                'extraTa',
                'bonus'
            ]);

            $table->double('grossPay',15,2);
            $table->double('weeklyPay',15,2);
            $table->double('holidayPay',15,2);
            $table->double('grossTa',15,2);
            $table->double('fhRate',15,2);

        });
    }
}
