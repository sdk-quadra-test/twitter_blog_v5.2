<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNoticeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notice', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('tweet_id')->unsigned()->nullable(false);
            $table->foreign('tweet_id')->references('id')->on('tweet');

            $table->integer('to_user_id')->unsigned()->nullable(false)->comment('通知対象者');;
            $table->foreign('to_user_id')->references('id')->on('user');

            $table->boolean('is_read')->default(0)->comment('通知既読判定');

            $table->boolean('is_deleted')->default(0);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('notice');
    }
}
