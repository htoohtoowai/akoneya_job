<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBannedStatusInJobsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $driver = Schema::connection($this->getConnection())->getConnection()->getDriverName(); 

        Schema::table('jobs', function (Blueprint $table) use ($driver) {
            if ('sqlite' === $driver) {
                $table->boolean('banned_status')->after("photo")->default('0');
            } else {
                $table->boolean('banned_status')->after("photo")->comment("1:banned")->default('0');
            }
        });
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('jobs', function (Blueprint $table) {
            $table->dropColumn('banned_status');
        });
    }
}
