<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNewsfeedShareHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('newsfeed_share_histories')) {
            Schema::create('newsfeed_share_histories', function (Blueprint $table) {
                $table->id();
                $table->unsignedInteger('newsfeed_id');
                $table->unsignedInteger('user_id');
                $table->unsignedInteger('shared_newsfeed_id');
                $table->unsignedInteger('shared_user_id');
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
        Schema::dropIfExists('newsfeed_share_histories');
    }
}
