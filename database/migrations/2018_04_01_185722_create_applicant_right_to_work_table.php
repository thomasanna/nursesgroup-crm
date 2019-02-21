<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateApplicantRightToWorkTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('applicant_right_to_works', function (Blueprint $table) {
            $table->increments('applicantRightToWorkId');
            $table->integer('applicantId')->unsigned();
            $table->integer('nationality')->unsigned()->nullable();
            $table->string('passportNumber')->nullable();
            $table->string('passportPlaceOfIssue')->nullable();
            $table->date('passportDateOfIssue')->nullable();
            $table->date('passportExpiryDate')->nullable();
            $table->string('passportDocumentFile')->nullable();
            $table->string('nokForName')->nullable();
            $table->string('nokSurName')->nullable();
            $table->string('nokRelationship')->nullable();
            $table->text('nokAddress')->nullable();
            $table->string('nokPostCode')->nullable();
            $table->string('nokPhone')->nullable();
            $table->string('nokMobile')->nullable();
            $table->string('nokEmail')->nullable();
            $table->tinyInteger('visaType')->nullable()->comment('1:UK National,2:Permenent Residence,3:EU National,4:Student Visa,5:Work Permit');
            $table->string('visaNumber')->nullable();
            $table->string('visaPlaceOfIssue')->nullable();
            $table->date('visaDateOfIssue')->nullable();
            $table->date('visaExpiryDate')->nullable();
            $table->string('visaDocumentFile')->nullable();
            $table->tinyInteger('rightToWorkWithoutPermission')->nullable()->comment('1:Yes,2:No');
            $table->string('checkedBy')->nullable();
            $table->date('checkedDate')->nullable();
            $table->tinyInteger('disciplinaryProcedure')->nullable()->comment('1:Yes,2:No');
            $table->tinyInteger('pendingInvestigation')->nullable()->comment('1:Yes,2:No');
            $table->text('medicalCondition')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('applicant_right_to_works');
    }
}
