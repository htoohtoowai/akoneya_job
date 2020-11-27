<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNewsfeedsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('newsfeeds')) {
            Schema::create('newsfeeds', function (Blueprint $table) {
                $table->id();
                $table->unsignedInteger('client_user_id');
                $table->text('content', 3000)->nullable();
                $table->unsignedInteger('shared_newsfeed_id')->nullable();
                $table->integer('privacy')->comment("1:public,2:friends,3:onlyme")->nullable();
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
        Schema::dropIfExists('newsfeeds');
    }
}
