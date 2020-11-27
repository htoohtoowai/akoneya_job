<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNewsfeedCommentLikesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('newsfeed_comment_likes')) {
            Schema::create('newsfeed_comment_likes', function (Blueprint $table) {
                $table->id();
                $table->unsignedInteger('client_user_id');
                $table->unsignedInteger('newsfeed_id');
                $table->unsignedInteger('newsfeed_comment_id');
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
        Schema::dropIfExists('newsfeed_comment_likes');
    }
}
