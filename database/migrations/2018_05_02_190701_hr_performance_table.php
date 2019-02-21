<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class HrPerformanceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hr_performance', function (Blueprint $table) {
            $table->increments('performanceId');
            $table->integer('hrId')->unsigned()->comment('hr login id');
            $table->boolean('action')->nullable()
            ->comment('1:Applicant to Terminated Applicant,2:Applicant to Active Staff, 3: Active Staff to Inactive Staff,4: Inactive staff to Terminated Staffs','5:Terminated Applicant to Active APplicants');
            $table->integer('applicantId')->nullable()->unsigned();
            $table->integer('staffId')->nullable()->unsigned();
            $table->date('actionDate')->nullable();
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
        Schema::dropIfExists('hr_performance');
    }
}
