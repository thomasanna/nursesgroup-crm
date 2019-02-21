<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTransportaionPicktimeAdded extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('transportations', function (Blueprint $table) {
            $table->integer('unitId')->unsigned()->nullable()->after('staffId');
            $table->date('date')->nullable()->after('unitId');
            $table->time('pickupTime')->nullable()->after('date');
            
            //
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('transportations', function (Blueprint $table) {
            $table->dropColumn(['unitId','date','pickupTime']);
            //
        });
    }
}
