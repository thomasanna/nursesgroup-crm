<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ClientUnitLogs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

         Schema::create('client_unit_logs', function (Blueprint $table) {
            $table->increments('clientUnitLogId');
            $table->unsignedInteger('clientUnitId');
            $table->string('content');
            $table->unsignedInteger('author');
            $table->tinyInteger('type')->comment('1:Logicaly,2:Manually')->default(1);
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
         Schema::dropIfExists('client_unit_logs');
    }
    
}

?>
