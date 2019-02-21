<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateApplicantTrainingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('applicant_trainings', function (Blueprint $table) {
            $table->increments('applicantTrainingId');
            $table->integer('applicantId')->unsigned();
            $table->integer('courseId')->unsigned();
            $table->string('provider')->nullable();
            $table->date('issueDate')->nullable();
            $table->date('expiryDate')->nullable();
            $table->tinyInteger('status')->default(1)->comment('1:Allocated,2:In-Progress;3:Completed');
            $table->text('comment')->nullable();
            $table->string('documentFile')->nullable();
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
        Schema::dropIfExists('applicant_trainings');
    }
}
