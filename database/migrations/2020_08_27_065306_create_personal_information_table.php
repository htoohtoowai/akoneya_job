<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePersonalInformationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('personal_information')) {
            Schema::create('personal_information', function (Blueprint $table) {
                $table->id();
                $table->unsignedInteger('client_user_id');
                $table->string('name')->nullable();
                $table->string('email')->nullable();
                $table->string('image')->nullable();
                $table->string('cover_photo')->nullable();
                $table->text('quote')->nullable();
                $table->string('phone')->nullable();
                $table->string('gender', 10)->nullable();
                $table->date('date_of_birth')->nullable();
                $table->text('bio')->nullable();
                $table->text('address')->nullable();
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
        Schema::dropIfExists('personal_information');
    }
}
