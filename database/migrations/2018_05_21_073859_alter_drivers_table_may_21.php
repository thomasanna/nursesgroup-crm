<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterDriversTableMay21 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('drivers', function (Blueprint $table) {
            $table->string('email')->nullable()->after('vehicleRegNumber');
            $table->string('mobile',15)->nullable()->after('email');
            $table->unsignedInteger('branchId')->nullable()->after('mobile');
            $table->date('dateOfBirth')->nullable()->after('mobile');
            $table->date('joinedDate')->nullable()->after('dateOfBirth');
            $table->boolean('gender')->nullable()->after('joinedDate')->comment("1:Male,0:Female");
            $table->string('whatsappNumber',15)->nullable()->after('mobile');
            $table->string('lanLineNumber',15)->nullable()->after('whatsappNumber');
            $table->string('address')->nullable()->after('lanLineNumber');
            $table->string('pincode',10)->nullable()->after('address');
            $table->string('latestTaxBand')->nullable()->after('pincode');
            $table->string('niNumber')->nullable()->after('latestTaxBand');
            $table->string('photo')->nullable()->after('niNumber');
            $table->string('niDocumentFile')->nullable()->after('photo');
            $table->unsignedInteger('zoneId')->after('branchId');
            $table->tinyInteger('paymentMode')->after('zoneId')->comment("1:Self,2:Payee");
            $table->string('selfPaymentCompanyName')->after('paymentMode')->nullable();
            $table->string('selfPaymentCompanyNumber')->after('selfPaymentCompanyName')->nullable();
            $table->text('selfPaymentCompanyRegAddress')->after('selfPaymentCompanyNumber')->nullable();

            $table->string('nokFullName')->nullable()->after('selfPaymentCompanyRegAddress');
            $table->string('nokRelationship')->nullable()->after('nokFullName');
            $table->string('nokMobile')->nullable()->after('nokRelationship');
            $table->string('nokEmail')->nullable()->after('nokMobile');
            $table->text('nokAddress')->nullable()->after('nokEmail');
            $table->string('nokPostCode')->nullable()->after('nokAddress');
            $table->string('nokPhone')->nullable()->after('nokPostCode');
            $table->double('ratePerMile',15,2)->nullable()->after('nokPhone');
            $table->double('extraStaffRate',15,2)->nullable()->after('ratePerMile');

            $table->integer('bankSortCodeA')->after('extraStaffRate')->nullable();
            $table->integer('bankSortCodeB')->after('bankSortCodeA')->nullable();
            $table->integer('bankSortCodeC')->after('bankSortCodeB')->nullable();
            $table->bigInteger('bankAccountNumber')->after('bankSortCodeC')->nullable();

            $table->dropColumn('vehicleRegNumber');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('drivers', function (Blueprint $table) {
            $table->dropColumn([
              'email','mobile','branchId',
              'dateOfBirth','joinedDate','gender',
              'whatsappNumber','lanLineNumber','address',
              'pincode','latestTaxBand','niNumber',
              'photo','niDocumentFile','zoneId',
              'paymentMode','selfPaymentCompanyName','selfPaymentCompanyNumber',
              'selfPaymentCompanyRegAddress','nokFullName','nokRelationship',
              'nokMobile','nokEmail','nokAddress',
              'nokPostCode','nokPhone','ratePerMile',
              'extraStaffRate','bankSortCodeA','bankSortCodeB',
              'bankSortCodeC','bankAccountNumber'
            ]);

        });
    }
}
