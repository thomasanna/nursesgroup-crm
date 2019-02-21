<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterBookingsTableVersionApril23 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn(['bonusReason','bonusAutherizedBy','taReason','taAutherizedBy']);
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
          $table->tinyInteger('bonusReason')->nullable()->comment('1:Last Minute Shift,2:Emergency')->after('bonus');
          $table->string('bonusAutherizedBy')->nullable()->after('bonusReason');
          $table->tinyInteger('taReason')->nullable()->comment('1:Last Minute Shift,2:Emergency')->after('transportAllowence');
          $table->string('taAutherizedBy')->nullable()->after('taReason');
        });
    }
}
