<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNewsfeedReportTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('newsfeed_report')) {
            Schema::create('newsfeed_report', function (Blueprint $table) {
                $table->id();
                $table->unsignedInteger('client_user_id');
                $table->unsignedInteger('reason_id');
                $table->unsignedInteger('newsfeed_id')->nullable();
                $table->unsignedInteger('report_to')->nullable();
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
        Schema::dropIfExists('newsfeed_report');
    }
}
