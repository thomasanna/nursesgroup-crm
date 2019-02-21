<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterApplicantsRtwVersionOne extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('applicant_right_to_works', function (Blueprint $table) {
            $table->text('visaComments')->after('visaDocumentFile')->nullable();
            $table->tinyInteger('visaExternalVerificationRequired')->after('visaComments')->nullable();
            $table->date('visaFollowUpDate')->after('visaExternalVerificationRequired')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('applicant_right_to_works', function (Blueprint $table) {
            $table->dropColumn(['visaComments','visaExternalVerificationRequired','visaFollowUpDate']);
        });
    }
}
