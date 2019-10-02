<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeFollowTable1909271501 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('follow', function (Blueprint $table) {
            $table->unique(['user_id', 'following_user_id']);
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('follow', function (Blueprint $table) {
            $table->dropUnique(['user_id', 'following_user_id']);
        });

    }
}
