<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterClientUnitMobilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::table('client_unit_mobiles', function (Blueprint $table) {
          $table->string('title')->after('clientUnitId')->nullable();
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      Schema::table('client_unit_mobiles', function (Blueprint $table) {
          $table->dropColumn('title');
      });
    }
}
