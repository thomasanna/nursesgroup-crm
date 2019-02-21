<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterBranchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::table('branches', function (Blueprint $table) {
        $table->text('address')->after('name');
        $table->string('phone',15)->after('address');
        $table->string('mobile',15)->after('phone');
        $table->string('email')->after('mobile');
        $table->tinyInteger('type')->after('email')->comment('1:HQ,2:Administrator,3:Branch,4:Satellite Center');
        $table->string('personInCharge')->after('type');
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      Schema::table('branches', function (Blueprint $table) {
          $table->dropColumn('address');
          $table->dropColumn('phone');
          $table->dropColumn('mobile');
          $table->dropColumn('email');
          $table->dropColumn('type');
          $table->dropColumn('personInCharge');
      });

    }
}
