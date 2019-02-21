<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStaffDbsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('staff_dbs', function (Blueprint $table) {
          $table->increments('staffDbsId');
          $table->integer('staffId')->unsigned();
          $table->string('dbsNo')->nullable();
          $table->date('issueDate')->nullable();
          $table->string('registeredBody')->nullable();
          $table->string('certificateNumber')->nullable();
          $table->string('documentFile')->nullable();
          $table->date('certificatePrint')->nullable();
          $table->string('checkedPerformedBy')->nullable();
          $table->date('checkedPerformedByDate')->nullable();
          $table->tinyInteger('statusDbs')->comment("1:Waiting for Address Proof,2:Applied,3:Approved,4:Rejected,5:Previous DBS")->nullable();;
          $table->timestamp('statusDbsChangedTime')->nullable();
          $table->tinyInteger('type')->comment('1:Basic,2:Enhanced')->nullable();;
          $table->tinyInteger('updateService')->comment('1:Yes,2:No')->nullable();;
          $table->string('applicationNumber')->nullable();
          $table->date('appliedDate')->nullable();
          $table->string('submittedBy')->nullable();
          $table->double('amountPaid',15,2)->nullable();
          $table->tinyInteger('paidBy')->nullable()->comment('1:NG,2:Staff,Update Service');
          $table->tinyInteger('policeRecordsOption')->nullable()->comment('1:NON RECORDED,2:RECORDED,3:NOT REQUESTED')->default(1);
          $table->tinyInteger('section142Option')->nullable()->comment('1:NON RECORDED,2:RECORDED,3:NOT REQUESTED')->default(1);
          $table->tinyInteger('childActListOption')->nullable()->comment('1:NON RECORDED,2:RECORDED,3:NOT REQUESTED')->default(1);
          $table->tinyInteger('CpoRelevantOption')->nullable()->comment('1:NON RECORDED,2:RECORDED,3:NOT REQUESTED')->default(1);
          $table->tinyInteger('vulnerableAdultOption')->nullable()->comment('1:NON RECORDED,2:RECORDED,3:NOT REQUESTED')->default(1);
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
        Schema::dropIfExists('staff_dbs');
    }
}
