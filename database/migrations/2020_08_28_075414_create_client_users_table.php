<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('client_users')) {
            Schema::create('client_users', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('email')->nullable();
                $table->string('phone', 15)->nullable();
                $table->string('otp_code', 10)->nullable();
                $table->timestamp('otp_attempt_time')->nullable();
                $table->string('active_status', 10)->nullable();
                $table->string('login_type', 20);
                $table->integer('wrong_time')->nullable();
                $table->text('facebook_id')->nullable();
                $table->text('email_id')->nullable();
                $table->text('apple_id')->nullable();
                $table->timestamp('email_verified_at')->nullable();
                $table->string('password', 225)->nullable();
                $table->longText('remember_token')->nullable();
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
        Schema::dropIfExists('client_users');
    }
}
