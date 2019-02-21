<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStaffRightToWorksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('staff_right_to_works', function (Blueprint $table) {
          $table->increments('staffRightToWorkId');
          $table->integer('staffId')->unsigned();
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
          $table->text('visaComments')->nullable();
          $table->tinyInteger('visaExternalVerificationRequired')->nullable();
          $table->date('visaFollowUpDate')->nullable();
          $table->string('checkedBy')->nullable();
          $table->date('checkedDate')->nullable();
          $table->tinyInteger('disciplinaryProcedure')->nullable()->comment('1:Yes,2:No');
          $table->text('disciplinaryProcedureComment')->nullable();
          $table->tinyInteger('pendingInvestigation')->nullable()->comment('1:Yes,2:No');
          $table->text('pendingInvestigationComment')->nullable();
          $table->tinyInteger('medicalCondition')->nullable()->comment('1:Yes,2:No');
          $table->text('medicalConditionComment')->nullable();
          $table->tinyInteger('status')->nullable()->comment('
          1:Permitted to work without any further documents,
          2:Required documents – No Work,
          3:Permitted to work - Limited hours work
          ');
          $table->integer("maximumPermittedWeeklyHours")->default(90);
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
        Schema::dropIfExists('staff_right_to_works');
    }
}
