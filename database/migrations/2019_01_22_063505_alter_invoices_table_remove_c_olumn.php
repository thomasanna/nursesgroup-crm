<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterInvoicesTableRemoveCOlumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropColumn([
                'totalHr',
                'totTa',
                'totEnic',
                'lineTotal',
            ]);

            $table->double('hourlyRate',15,2)->nullable()->after('timesheetId');
            $table->double('enic',15,2)->nullable()->after('hourlyRate');
            $table->double('ta',15,2)->nullable()->after('enic');
            $table->double('unitDistence',15,2)->nullable()->after('ta');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->double('totalHr',15,2)->nullable();
            $table->double('totTa',15,2)->nullable();
            $table->double('totEnic',15,2)->nullable();
            $table->double('lineTotal',15,2)->nullable();

            $table->dropColumn([
                'hourlyRate',
                'enic',
                'ta',
                'unitDistence',
            ]);
        });
    }
}
