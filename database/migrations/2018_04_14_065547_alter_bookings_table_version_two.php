<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterBookingsTableVersionTwo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bookings', function (Blueprint $table) {
          $table->tinyInteger('staffAllocateStatus')->nullable()->comment('1:Dummy,2:Confirmed,3:Potential')->after('staffId');
          $table->tinyInteger('modeOfTransport')->nullable()->comment('1:Self,2:Self+Lift,3:Lift,4:Transport Required')->after('staffAllocateStatus');
          $table->string('outBoundDriver')->nullable()->after('modeOfTransport');
          $table->string('inBoundDriver')->nullable()->after('outBoundDriver');
          $table->double('bonus',15,2)->nullable()->after('inBoundDriver');
          $table->tinyInteger('bonusReason')->nullable()->comment('1:Last Minute Shift,2:Emergency')->after('bonus');
          $table->string('bonusAutherizedBy')->nullable()->after('bonusReason');

          $table->double('transportAllowence',15,2)->nullable()->after('bonusAutherizedBy');
          $table->tinyInteger('taReason')->nullable()->comment('1:Last Minute Shift,2:Emergency')->after('transportAllowence');
          $table->string('taAutherizedBy')->nullable()->after('taReason');
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
            'staffAllocateStatus',
            'modeOfTransport',
            'outBoundDriver',
            'inBoundDriver',
            'bonus',
            'bonusReason',
            'bonusAutherizedBy',
            'transportAllowence',
            'taReason',
            'taAutherizedBy',
          ]);
        });
    }
}
