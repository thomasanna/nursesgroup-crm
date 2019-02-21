<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterApplicantDbsTableOne extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('applicant_dbs', function (Blueprint $table) {
            $table->renameColumn('issuedBy','registeredBody');
            $table->string('certificateNumber')->nullable()->after('issuedBy');
            $table->date('certificatePrint')->nullable()->after('certificateNumber');
            $table->string('checkedPerformedBy')->nullable()->after('certificatePrint');
            $table->date('checkedPerformedByDate')->nullable()->after('checkedPerformedBy');
            $table->tinyInteger('policeRecordsOption')->nullable()->after('paidBy')->comment('1:NON RECORDED,2:RECORDED,3:NOT REQUESTED')->default(1);
            $table->tinyInteger('section142Option')->nullable()->after('policeRecordsOption')->comment('1:NON RECORDED,2:RECORDED,3:NOT REQUESTED')->default(1);
            $table->tinyInteger('childActListOption')->nullable()->after('section142Option')->comment('1:NON RECORDED,2:RECORDED,3:NOT REQUESTED')->default(1);
            $table->tinyInteger('vulnerableAdultOption')->nullable()->after('childActListOption')->comment('1:NON RECORDED,2:RECORDED,3:NOT REQUESTED')->default(1);
            $table->tinyInteger('CpoRelevantOption')->nullable()->after('childActListOption')->comment('1:NON RECORDED,2:RECORDED,3:NOT REQUESTED')->default(1);
            $table->string('documentFile')->nullable()->after('certificateNumber');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('applicant_dbs', function (Blueprint $table) {
            $table->renameColumn('registeredBody','issuedBy');
            $table->dropColumn([
              'certificateNumber',
              'certificatePrint',
              'checkedPerformedBy',
              'checkedPerformedByDate',
              'policeRecordsOption',
              'section142Option',
              'childActListOption',
              'vulnerableAdultOption',
              'CpoRelevantOption',
              'documentFile',
            ]);
        });
    }
}
