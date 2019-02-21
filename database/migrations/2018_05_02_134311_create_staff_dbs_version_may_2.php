<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStaffDbsVersionMay2 extends Migration
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
          $table->tinyInteger('dbsType')->nullable()->comment('1:New Application,2:Valid DBS Available,3:Update Service User');
          $table->string('apctnNumber')->nullable();
          $table->date('apctnAppliedDate')->nullable();
          $table->string('apctnSubmittedBy')->nullable();
          $table->double('apctnAmountPaid',15,2)->nullable();
          $table->tinyInteger('apctnPaidBy')->nullable()->comment('1:NG,2:Staff,3:Update Service');
          $table->date('apctnFollowUpDate')->nullable();
          $table->tinyInteger('apctnStatus')->comment('1:Search In Progress,2:Search On Hold,3:Certificate Issued')->nullable();;
          $table->string('validDbsNumber')->nullable();
          $table->date('validIssueDate')->nullable();
          $table->string('validRegisteredBody')->nullable();
          $table->tinyInteger('validType')->comment('1:Basic,2:Enhanced')->nullable();
          $table->string('validCertificate')->nullable();
          $table->tinyInteger('validPoliceRecordsOption')->nullable()->comment('1:NON RECORDED,2:RECORDED,3:NOT REQUESTED');
          $table->tinyInteger('validSection142Option')->nullable()->comment('1:NON RECORDED,2:RECORDED,3:NOT REQUESTED');
          $table->tinyInteger('validChildActListOption')->nullable()->comment('1:NON RECORDED,2:RECORDED,3:NOT REQUESTED');
          $table->tinyInteger('validVulnerableAdultOption')->nullable()->comment('1:NON RECORDED,2:RECORDED,3:NOT REQUESTED');
          $table->tinyInteger('validCpoRelevantOption')->nullable()->comment('1:NON RECORDED,2:RECORDED,3:NOT REQUESTED');
          $table->string('updateServiceNumber')->nullable();
          $table->date('updateServiceCheckedDate')->nullable();
          $table->string('updateServiceCheckedBy')->nullable();
          $table->tinyInteger('updateServiceStatus')->comment('1:DBS Verified,2:DBS Unable to Verified')->nullable();
          $table->tinyInteger('updateServicePoliceRecordsOption')->nullable()->comment('1:NON RECORDED,2:RECORDED,3:NOT REQUEST');
          $table->tinyInteger('updateServiceSection142Option')->nullable()->comment('1:NON RECORDED,2:RECORDED,3:NOT REQUESTED');
          $table->tinyInteger('updateServiceChildActListOption')->nullable()->comment('1:NON RECORDED,2:RECORDED,3:NOT REQUESTED');
          $table->tinyInteger('updateServiceVulnerableAdultOption')->nullable()->comment('1:NON RECORDED,2:RECORDED,3:NOT REQUESTED');
          $table->tinyInteger('updateServiceCpoRelevantOption')->nullable()->comment('1:NON RECORDED,2:RECORDED,3:NOT REQUESTED');
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
