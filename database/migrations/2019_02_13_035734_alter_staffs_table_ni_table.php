<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterStaffsTableNiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('staffs', function (Blueprint $table) {
            $table->string('niTable')->nullable()->after('niDocumentFile');
            $table->string('studentLoan')->nullable()->after('niTable');
        });
        Schema::table('applicants', function (Blueprint $table) {
            $table->string('niTable')->nullable()->after('niDocumentFile');
            $table->string('studentLoan')->nullable()->after('niTable');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('staffs', function (Blueprint $table) {
            $table->dropColumn(['niTable','studentLoan']);
        });

        Schema::table('applicants', function (Blueprint $table) {
            $table->dropColumn(['niTable','studentLoan']);
        });
    }
}
