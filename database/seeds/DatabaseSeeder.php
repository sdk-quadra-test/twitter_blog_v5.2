<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('notice')->truncate();

        $this->call(UserTableSeeder::class);
        $this->call(FollowTableSeeder::class);
        $this->call(TweetTableSeeder::class);
    }
}
