<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBranchContactsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('branch_contacts', function (Blueprint $table) {
            $table->increments('branchContactId');
            $table->unsignedInteger('branchId');
            $table->string('name')->nullable();
            $table->boolean('status')->default(1)->comment('1:Active,0:Iactive');
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
        Schema::dropIfExists('branch_contacts');
    }
}
