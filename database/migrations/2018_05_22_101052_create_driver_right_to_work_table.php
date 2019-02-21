<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDriverRightToWorkTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('driver_right_to_works', function (Blueprint $table) {
          $table->increments('driverRightToWorkId');
          $table->integer('driverId')->unsigned();
          $table->integer('nationality')->unsigned()->nullable();
          $table->string('passportNumber')->nullable();
          $table->string('passportPlaceOfIssue')->nullable();
          $table->date('passportDateOfIssue')->nullable();
          $table->date('passportExpiryDate')->nullable();
          $table->string('passportDocumentFile')->nullable();
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
        Schema::dropIfExists('driver_right_to_works');
    }
}
