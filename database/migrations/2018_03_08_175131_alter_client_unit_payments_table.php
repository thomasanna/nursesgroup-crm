<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterClientUnitPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('client_unit_payments', function (Blueprint $table) {
            $table->double('enicWeekday',15,2)->after('payRateWeekday')->nullable();
            $table->double('enicWeekNight',15,2)->after('payRateWeekNight')->nullable();
            $table->double('enicWeekendDay',15,2)->after('payRateWeekendDay')->nullable();
            $table->double('enicWeekendNight',15,2)->after('payRateWeekendNight')->nullable();
            $table->double('enicSpecialBhday',15,2)->after('payRateSpecialBhday')->nullable();
            $table->double('enicSpecialBhnight',15,2)->after('payRateSpecialBhnight')->nullable();
            $table->double('enicBhday',15,2)->after('payRateBhday')->nullable();
            $table->double('enicBhnight',15,2)->after('payRateBhnight')->nullable();
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
            $table->dropColumn('enicWeekday');
            $table->dropColumn('enicWeekNight');
            $table->dropColumn('enicWeekendDay');
            $table->dropColumn('enicWeekendNight');
            $table->dropColumn('enicSpecialBhday');
            $table->dropColumn('enicSpecialBhnight');
            $table->dropColumn('enicBhday');
            $table->dropColumn('enicBhnight');
        });
    }
}
