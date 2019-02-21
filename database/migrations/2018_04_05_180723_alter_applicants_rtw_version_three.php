<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterApplicantsRtwVersionThree extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('applicant_right_to_works', function (Blueprint $table) {
          $table->text('disciplinaryProcedureComment')->after('disciplinaryProcedure')->nullable();
          $table->text('pendingInvestigationComment')->after('pendingInvestigation')->nullable();
          $table->tinyInteger('medicalCondition')->after('pendingInvestigationComment')->nullable()->comment('1:Yes,2:No');
          $table->text('medicalConditionComment')->after('medicalCondition')->nullable();
          $table->tinyInteger('status')->after('medicalConditionComment')->nullable()->comment('
          1:Permitted to work without any further documents,
          2:Required documents – No Work,
          3:Permitted to work - Limited hours work
          ');
          $table->integer("maximumPermittedWeeklyHours")->after('status')->default(90);
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
            $table->dropColumn([
              'medicalCondition',
              'disciplinaryProcedureComment',
              'pendingInvestigationComment',
              'medicalConditionComment',
              'status',
            'maximumPermittedWeeklyHours']);
        });
    }
}
