<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJobsReportTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('jobs_report')) {
            Schema::create('jobs_report', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('client_user_id');
                $table->unsignedBigInteger('reason_id');
                $table->unsignedBigInteger('job_id')->nullable();
                $table->dateTime('created_at', 0);
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('jobs_report');
    }
}
