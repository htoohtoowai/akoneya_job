<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJobsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jobs', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('client_user_id');
            $table->text('title', 200);
            $table->text('company_name', 200);
            $table->integer('job_type');
            $table->integer('zone_id');
            $table->text('salary', 200);
            $table->text('address', 500);
            $table->text('phone', 50);
            $table->text('email', 50)->nullable();
            $table->integer('gender');
            $table->text('description', 500)->nullable();
            $table->text('requirement', 500)->nullable();
            $table->text('photo', 150)->nullable();
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
        Schema::dropIfExists('jobs');
    }
}
