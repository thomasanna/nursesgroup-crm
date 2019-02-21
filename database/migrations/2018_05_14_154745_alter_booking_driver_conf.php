<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterBookingDriverConf extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->integer('outBoundDriverType')->nullable()->after('outBoundDriver')->comment("1:Private Driver,2:Possible Lift,3:Public Transport");
            $table->integer('outBoundDriverId')->nullable()->after('outBoundDriverType')->comment("Id based on outBoundDriverType");
            $table->integer('inBoundDriverType')->nullable()->after('outBoundDriverId')->comment("1:Private Driver,2:Possible Lift,3:Public Transport");
            $table->integer('inBoundDriverId')->nullable()->after('inBoundDriverType')->comment("Id based on inBoundDriverType");
            $table->integer('bonusReason')->nullable()->after('inBoundDriverId');
            $table->integer('bonusAuthorizedBy')->nullable()->after('bonusReason');
            $table->double('extraTA',15,2)->nullable()->default(0)->after('bonusAuthorizedBy');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn([
                'outBoundDriverType',
                'outBoundDriverId',
                'inBoundDriverType',
                'inBoundDriverId',
                'bonusReason',
                'bonusAuthorizedBy',
                'extraTA',
            ]);
        });
    }
}
