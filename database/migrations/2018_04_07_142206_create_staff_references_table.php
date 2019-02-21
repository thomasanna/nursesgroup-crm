<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStaffReferencesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('staff_references', function (Blueprint $table) {
          $table->increments('staffReferenceId');
          $table->integer('staffId')->unsigned();
          $table->string('forName')->nullable();
          $table->string('surName')->nullable();
          $table->string('position')->nullable();
          $table->text('address')->nullable();
          $table->string('phone')->nullable();
          $table->string('email')->nullable();
          $table->string('website')->nullable();
          $table->date('sentDate')->nullable();
          $table->tinyInteger('modeOfReference')->nullable()->comment("1:Phone,2:Email,3:Letter,4:Verbal");
          $table->string('sentBy')->nullable();
          $table->tinyInteger('status')->nullable()->comment('1:Sent,2:1st Follow Up,3:2nd Follow Up,4:Inform Staff5,:Rejected,6:Success');
          $table->date('onStatusChanged')->nullable();
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
        Schema::dropIfExists('staff_references');
    }
}
