<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdminsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admins', function (Blueprint $table) {
            $table->increments('adminId');
            $table->string('name');
            $table->smallInteger('type')->comment('1:admin,2:accountant,3: hr,4:payroll,5:client manager,6:guest');
            $table->string('email',50)->unique();
            $table->string('username',50)->unique();
            $table->string('password',100);
            $table->string('secret_pin',100);
            $table->rememberToken();
            $table->boolean('status')->default(1);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('admins');
    }
}
