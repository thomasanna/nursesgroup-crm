<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTaxYearTableDefault extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tax_years', function (Blueprint $table) {
            $table->boolean('default')->after('taxYearTo')->default(0)->comment('0:False,1:True');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tax_years', function (Blueprint $table) {
            $table->dropColumn('default');
        });
    }
}
