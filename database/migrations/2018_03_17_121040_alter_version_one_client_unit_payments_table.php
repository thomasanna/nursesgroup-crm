<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterVersionOneClientUnitPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('client_unit_payments', function (Blueprint $table) {
          $table->renameColumn('payRateWeekday', 'dayMonday');
          $table->renameColumn('enicWeekday', 'nightMonday');
          $table->renameColumn('payRateWeekNight', 'dayTuesday');
          $table->renameColumn('enicWeekNight', 'nightTuesday');
          $table->renameColumn('payRateWeekendDay', 'dayWednesday');
          $table->renameColumn('enicWeekendDay', 'nightWednesday');
          $table->renameColumn('payRateWeekendNight', 'dayThursday');
          $table->renameColumn('enicWeekendNight', 'nightThursday');
          $table->renameColumn('payRateSpecialBhday', 'dayFriday');
          $table->renameColumn('enicSpecialBhday', 'nightFriday');
          $table->renameColumn('payRateSpecialBhnight', 'daySaturday');
          $table->renameColumn('enicSpecialBhnight', 'nightSaturday');
          $table->renameColumn('payRateBhday', 'daySunday');
          $table->renameColumn('enicBhday', 'nightSunday');
          $table->renameColumn('payRateBhnight', 'bhDay');
          $table->renameColumn('enicBhnight', 'bhNight');
          $table->double('taNoOfMiles')->nullable()->after('transportAllowance');
          $table->double('splBhDay')->nullable()->after('enicBhnight');
          $table->double('splBhNight')->nullable()->after('splBhDay');
          $table->tinyInteger('rateType')->after('staffCategoryId')->comment('1=Rate,2=ENIC');
          $table->renameColumn('transportAllowance', 'taPerMile');


        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('client_unit_payments', function (Blueprint $table) {
          $table->renameColumn('dayMonday', 'payRateWeekday');
          $table->renameColumn('nightMonday', 'enicWeekday');
          $table->renameColumn('dayTuesday', 'payRateWeekNight');
          $table->renameColumn('nightTuesday', 'enicWeekNight');
          $table->renameColumn('dayWednesday', 'payRateWeekendDay');
          $table->renameColumn('nightWednesday', 'enicWeekendDay');
          $table->renameColumn('dayThursday', 'payRateWeekendNight');
          $table->renameColumn('nightThursday', 'enicWeekendNight');
          $table->renameColumn('dayFriday', 'payRateSpecialBhday');
          $table->renameColumn('nightFriday', 'enicSpecialBhday');
          $table->renameColumn('daySaturday', 'payRateSpecialBhnight');
          $table->renameColumn('nightSaturday', 'enicSpecialBhnight');
          $table->renameColumn('daySunday', 'payRateBhday');
          $table->renameColumn('nightSunday', 'enicBhday');
          $table->renameColumn('bhDay', 'payRateBhnight');
          $table->renameColumn('bhNight', 'enicBhnight');
          $table->renameColumn('taPerMile', 'transportAllowance');
          $table->dropColumn(['splBhDay','splBhNight','taNoOfMiles','RateType']);
        });
    }
}
