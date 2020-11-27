<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNewsfeedCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('newsfeed_comments')) {
            Schema::create('newsfeed_comments', function (Blueprint $table) {
                $table->id();
                $table->unsignedInteger('client_user_id');
                $table->unsignedInteger('newsfeed_id');
                $table->text('message', 500)->nullable();
                $table->unsignedInteger('replied_newsfeed_comment_id')->nullable();
                $table->string('media_path')->nullable();
                $table->integer('media_type')->nullable();
                $table->integer('status')->nullable();
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
        Schema::dropIfExists('newsfeed_comments');
    }
}
