<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterApplicantsTable6April extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('applicants', function (Blueprint $table) {
            $table->integer('bankSortCodeA')->after('bandId')->nullable();
            $table->integer('bankSortCodeB')->after('bankSortCodeA')->nullable();
            $table->integer('bankSortCodeC')->after('bankSortCodeB')->nullable();
            $table->bigInteger('bankAccountNumber')->after('bankSortCodeC')->nullable();
            $table->string('niNumber')->after('bankAccountNumber')->nullable();
            $table->string('niDocumentFile')->after('niNumber')->nullable();
            $table->string('latestTaxBand')->after('niDocumentFile')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('applicants', function (Blueprint $table) {
          $table->dropColumn([
            'bankSortCodeA',
            'bankSortCodeB',
            'bankSortCodeC',
            'bankAccountNumber',
            'niNumber',
            'niDocumentFile',
            'latestTaxBand']);
        });
    }
}
