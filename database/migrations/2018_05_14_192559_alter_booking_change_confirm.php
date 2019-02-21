<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterBookingChangeConfirm extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->tinyInteger('cancelImapctFactor')->nullable()->after('cancelTime');
            $table->tinyInteger('cancelInformUnit')->nullable()->after('cancelImapctFactor');
            $table->text('cancelNotes')->nullable()->after('cancelInformUnit');
            $table->integer('cancelAuthorizedBy')->nullable()->after('cancelNotes');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn([
                'cancelImapctFactor',
                'cancelInformUnit',
                'cancelNotes',
                'cancelAuthorizedBy',
            ]);
        });
    }
}
