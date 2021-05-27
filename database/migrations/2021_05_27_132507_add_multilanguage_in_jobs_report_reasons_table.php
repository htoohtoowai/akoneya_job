<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMultilanguageInJobsReportReasonsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('jobs_report_reasons', function (Blueprint $table) {
            $table->string('name_mm')->nullable();
            $table->string('name_zh')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('jobs_report_reasons', function (Blueprint $table) {
            $table->dropColumn('name_mm');
        });

        Schema::table('jobs_report_reasons', function (Blueprint $table) {
            $table->dropColumn('name_zh');
        });
    }
}
