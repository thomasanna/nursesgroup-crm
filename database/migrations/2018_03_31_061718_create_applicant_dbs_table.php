<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateApplicantDbsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('applicant_dbs', function (Blueprint $table) {
            $table->increments('applicantDbsId');
            $table->integer('applicantId')->unsigned();
            $table->string('dbsNo')->nullable();
            $table->date('issueDate')->nullable();
            $table->string('issuedBy')->nullable();
            $table->tinyInteger('statusDbs')->comment("1:Waiting for Address Proof,2:Applied,3:Approved,4:Rejected,5:Previous DBS")->nullable();;
            $table->timestamp('statusDbsChangedTime')->nullable();
            $table->tinyInteger('type')->comment('1:Basic,2:Enhanced')->nullable();;
            $table->tinyInteger('updateService')->comment('1:Yes,2:No')->nullable();;
            $table->string('applicationNumber')->nullable();
            $table->date('appliedDate')->nullable();
            $table->string('submittedBy')->nullable();
            $table->double('amountPaid',15,2)->nullable();
            $table->tinyInteger('paidBy')->nullable()->comment('1:NG,2:Staff,Update Service');
            $table->boolean('status')->default(1);
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
        Schema::dropIfExists('applicant_dbs');
    }
}
