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
        DB::table('user')->insert([
        array(
			'id'       => '1',
			'name'     => 'user01',
			'password' => '1111',
            'disp_name'     => '表示名A',
            'profile'     => "profileAプロフィールA府露麩ぃ流A<strong>tag</strong><h1>h1タグ</h1><span style='color:red;'>red</span>"
        ),
        [
    'id'       => '2',
			'name'     => 'user02',
			'password' => '2222',
            'disp_name'     => '表示名B',
            'profile'     => "profileBプロフィールB府露麩ぃ流B<strong>tag</strong><h1>h1タグ</h1><span style='color:red;'>red</span>"
        ],
        [
        	'id'       => '3',
			'name'     => 'user03',
			'password' => '3333',
            'disp_name'     => '表示名C',
            'profile'     => "profileCプロフィールC府露麩ぃ流C<strong>tag</strong><h1>h1タグ</h1><span style='color:red;'>red</span>"
        ],
        [
        	'id'       => '4',
			'name'     => 'user04',
			'password' => '4444',
            'disp_name'     => '表示名D',
            'profile'     => "profileDプロフィールD府露麩ぃ流D<strong>tag</strong><h1>h1タグ</h1><span style='color:red;'>red</span>"
        ],
        [
        	'id'       => '5',
			'name'     => 'user05',
			'password' => '5555',
            'disp_name'     => '表示名E',
            'profile'     => "profileEプロフィールE府露麩ぃ流E<strong>tag</strong><h1>h1タグ</h1><span style='color:red;'>red</span>"
        ]
      ]);
    }
}
