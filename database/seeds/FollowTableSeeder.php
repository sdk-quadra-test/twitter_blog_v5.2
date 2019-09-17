<?php

use Illuminate\Database\Seeder;

class FollowTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('follow')->insert([
        [
        	'user_id'       => '1',
			'following_user_id'     => '2'
        ],
        [
        	'user_id'       => '1',
			'following_user_id'     => '3'
        ],
        [
        	'user_id'       => '1',
			'following_user_id'     => '4'
        ],
        [
        	'user_id'       => '1',
			'following_user_id'     => '5'
        ],

        [
        	'user_id'       => '2',
			'following_user_id'     => '1'
        ],
        [
        	'user_id'       => '2',
			'following_user_id'     => '3'
        ],

        [
        	'user_id'       => '3',
			'following_user_id'     => '4'
        ],
        [
        	'user_id'       => '3',
			'following_user_id'     => '5'
        ],

        [
        	'user_id'       => '4',
			'following_user_id'     => '3'
        ],

        [
        	'user_id'       => '5',
			'following_user_id'     => '2'
        ],
        [
        	'user_id'       => '5',
			'following_user_id'     => '4'
        ]

      ]);
    }
}
