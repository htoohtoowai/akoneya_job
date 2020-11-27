<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNewsfeedMediaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('newsfeed_media')) {
            Schema::create('newsfeed_media', function (Blueprint $table) {
                $table->id();
                $table->unsignedInteger('client_user_id');
                $table->unsignedInteger('newsfeed_id');
                $table->string('media_path')->nullable();
                $table->integer('media_type')->nullable();
                $table->timestamps();
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
        Schema::dropIfExists('newsfeed_media');
    }
}
