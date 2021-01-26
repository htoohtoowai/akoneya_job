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
        if (!Schema::hasTable('jobs')) {
            Schema::create('jobs', function (Blueprint $table) {
                $table->id();
                $table->unsignedInteger('client_user_id');
                $table->string('title', 200);
                $table->string('company_name', 200);
                $table->integer('job_type');
                $table->integer('zone_id');
                $table->string('salary', 200);
                $table->text('address', 500);
                $table->string('phone', 50);
                $table->string('email', 50)->nullable();
                $table->integer('gender');
                $table->text('description', 500)->nullable();
                $table->text('requirement', 500)->nullable();
                $table->string('photo', 150)->nullable();
                $table->timestamps();
                $table->index(['title','job_type','zone_id']);
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
        Schema::dropIfExists('jobs');
    }
}
