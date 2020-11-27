<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSavedJobsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('saved_jobs')) {
            Schema::create('saved_jobs',function (Blueprint $table) {
                    $table->id();
                    $table->unsignedInteger('job_id');
                    $table->unsignedInteger('client_user_id');
                }
            );
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('saved_jobs');
    }
}
