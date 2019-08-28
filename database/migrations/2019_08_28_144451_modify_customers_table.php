<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Carbon\Carbon;

class ModifyCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('customer', function ($table) {
            $table->bigInteger('user_id')->after('mob_phone')
                                ->unique()
                                ->nullable()
                                ->default(null);
        });

        Schema::table('users', function ($table) {
            $table->string('api_token', 80)->after('password')
                                ->unique()
                                ->nullable()
                                ->default(null);
            $table->dateTime('api_token_expire')->after('api_token')
                                ->nullable(false)
                                ->default(Carbon::now()->addHours(4));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('customer', function($table)
        {
            $table->dropColumn('user_id');
        });
    }
}
