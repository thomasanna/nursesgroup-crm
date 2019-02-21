<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTransportsTableOrderColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('transportations', function (Blueprint $table) {
            $table->smallInteger('order')->after('recordPaymentTime')->default(0);
            $table->year('taxYear')->after('order')->nullable();
            $table->smallInteger('payeeWeek')->after('taxYear')->nullable();
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
            $table->dropColumn(['order','taxYear','payeeWeek']);
        });
    }
}
