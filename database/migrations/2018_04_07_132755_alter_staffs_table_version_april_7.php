<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterStaffsTableVersionApril7 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('staffs', function (Blueprint $table) {
          $table->integer('bankSortCodeA')->after('bandId')->nullable();
          $table->integer('bankSortCodeB')->after('bankSortCodeA')->nullable();
          $table->integer('bankSortCodeC')->after('bankSortCodeB')->nullable();
          $table->bigInteger('bankAccountNumber')->after('bankSortCodeC')->nullable();
          $table->string('niNumber')->after('bankAccountNumber')->nullable();
          $table->string('niDocumentFile')->after('niNumber')->nullable();
          $table->string('latestTaxBand')->after('niDocumentFile')->nullable();
          $table->string('selfPaymentCompanyName')->after('paymentMode')->nullable();
          $table->string('selfPaymentCompanyNumber')->after('selfPaymentCompanyName')->nullable();
          $table->text('selfPaymentCompanyRegAddress')->after('selfPaymentCompanyNumber')->nullable();
          $table->string('selfPaymentCompanyLandLine')->after('selfPaymentCompanyRegAddress')->nullable();
          $table->string('selfPaymentCompanyAltPhone')->after('selfPaymentCompanyLandLine')->nullable();
          $table->text('selfPaymentCompanyBusAddress')->after('selfPaymentCompanyAltPhone')->nullable();
          $table->tinyInteger('selfPaymentCompanyType')->after('selfPaymentCompanyBusAddress')->nullable();
          $table->string('selfPaymentCompanyMobile',20)->after('selfPaymentCompanyType')->nullable();
          $table->string('selfPaymentCompanyEmail')->after('selfPaymentCompanyMobile')->nullable();
          $table->string('selfPaymentCompanyPersonInCharge')->after('selfPaymentCompanyEmail')->nullable();
          $table->integer('selfPaymentCompanyNumberOfBranches')->after('selfPaymentCompanyPersonInCharge')->nullable();
          $table->string('selfPaymentCompanyFax')->after('selfPaymentCompanyNumberOfBranches')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('staffs', function (Blueprint $table) {
            $table->dropColumn([
              'bankSortCodeA',
              'bankSortCodeB',
              'bankSortCodeC',
              'bankAccountNumber',
              'niNumber',
              'niDocumentFile',
              'latestTaxBand']);
            $table->dropColumn([
                'selfPaymentCompanyName',
                'selfPaymentCompanyNumber',
                'selfPaymentCompanyRegAddress',
                'selfPaymentCompanyLandLine',
                'selfPaymentCompanyAltPhone',
                'selfPaymentCompanyBusAddress',
                'selfPaymentCompanyType',
                'selfPaymentCompanyMobile',
                'selfPaymentCompanyEmail',
                'selfPaymentCompanyPersonInCharge',
                'selfPaymentCompanyNumberOfBranches',
                'selfPaymentCompanyFax',
              ]);
        });
    }
}
