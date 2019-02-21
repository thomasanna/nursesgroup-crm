<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropAlterUnitContactsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('client_unit_emails');
        Schema::dropIfExists('client_unit_mobiles');
        Schema::dropIfExists('client_unit_phones');
        Schema::create('client_unit_contacts', function (Blueprint $table) {
            $table->increments('clientUnitPhoneId');
            $table->integer('clientUnitId')->unsigned();
            $table->string('fullName')->nullable();
            $table->string('position')->nullable();
            $table->string('phone',15)->nullable();
            $table->string('email',100)->nullable();
            $table->string('mobile',20)->nullable();
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
        Schema::dropIfExists('client_unit_contacts');
    }
}
