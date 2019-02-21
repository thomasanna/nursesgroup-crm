<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTransportTableRemoveClub extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('transportations', function (Blueprint $table) {
            $table->dropColumn('clubId');
            $table->boolean('status')->default(1)->comment('1:Active,0:Inactive')->change();
            $table->double('aggreedAmount',15,2)->nullable()->after('pickupTime');
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
            $table->integer('clubId');
        });
    }
}
