<?php

use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //truncateする前に外部キー制約外す
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('user')->truncate();

        DB::table('user')->insert([
        array(
			'id'       => '1',
			'name'     => 'user01',
			'password' => Hash::make('1111'),
            'disp_name'     => '表示名A',
            'profile'     => "profileAプロフィールA府露麩ぃ流A<strong>tag</strong><h1>h1タグ</h1><span style='color:red;'>red</span>",
            'icon_url' => 'https://twitter-progra-master.s3-ap-northeast-1.amazonaws.com/anonymous/anonymous_1.png',
            'created_at' => '2019-09-01 00:00:00',
            'updated_at' => '2019-09-01 00:00:00'
        ),
        [
    'id'       => '2',
			'name'     => 'user02',
            'password' => Hash::make('2222'),
            'disp_name'     => '表示名B',
            'profile'     => "profileBプロフィールB府露麩ぃ流B<strong>tag</strong><h1>h1タグ</h1><span style='color:red;'>red</span>",
            'icon_url' => 'https://twitter-progra-master.s3-ap-northeast-1.amazonaws.com/anonymous/anonymous_2.png',
            'created_at' => '2019-09-02 00:00:00',
            'updated_at' => '2019-09-02 00:00:00'
        ],
        [
        	'id'       => '3',
			'name'     => 'user03',
            'password' => Hash::make('3333'),
            'disp_name'     => '表示名C',
            'profile'     => "profileCプロフィールC府露麩ぃ流C<strong>tag</strong><h1>h1タグ</h1><span style='color:red;'>red</span>",
            'icon_url' => 'https://twitter-progra-master.s3-ap-northeast-1.amazonaws.com/anonymous/anonymous_3.png',
            'created_at' => '2019-09-03 00:00:00',
            'updated_at' => '2019-09-03 00:00:00'
        ],
        [
        	'id'       => '4',
			'name'     => 'user04',
            'password' => Hash::make('4444'),
            'disp_name'     => '表示名D',
            'profile'     => "profileDプロフィールD府露麩ぃ流D<strong>tag</strong><h1>h1タグ</h1><span style='color:red;'>red</span>",
            'icon_url' => 'https://twitter-progra-master.s3-ap-northeast-1.amazonaws.com/anonymous/anonymous_4.png',
            'created_at' => '2019-09-04 00:00:00',
            'updated_at' => '2019-09-04 00:00:00'
        ],
        [
        	'id'       => '5',
			'name'     => 'user05',
            'password' => Hash::make('5555'),
            'disp_name'     => '表示名E',
            'profile'     => "profileEプロフィールE府露麩ぃ流E<strong>tag</strong><h1>h1タグ</h1><span style='color:red;'>red</span>",
            'icon_url' => 'https://twitter-progra-master.s3-ap-northeast-1.amazonaws.com/anonymous/anonymous_5.png',
            'created_at' => '2019-09-05 00:00:00',
            'updated_at' => '2019-09-05 00:00:00'
        ]
      ]);
    }
}
