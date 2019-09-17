<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the cアカウントontroller to call when that URI is requested.
|
*/

Route::get('/', function () {
    return view('welcome');
});

// tweet
Route::get('/tweet', 'TweetController@tweet')->name('tweet');
Route::post('/store_tweet', 'TweetController@store_tweet')->name('store_tweet');


// timeline
Route::get('/timeline', 'TimelineController@timeline')->name('timeline');
Route::post('/timeline', 'TimelineController@timeline')->name('timeline');

// user
Route::get('/user', 'UserController@get_all_user')->name('get_all_user');

// notice
Route::get('/notice', 'NoticeController@notice')->name('notice');
Route::post('/update_to_read', 'NoticeController@update_to_read')->name('update_to_read');


// profile
Route::get('/profile/edit', 'ProfileController@edit_profile')->name('edit_profile');
Route::put('/profile/edit', 'ProfileController@edit_profile')->name('edit_profile');

Route::get('/account/edit', 'ProfileController@edit_account')->name('edit_account');
Route::put('/account/edit', 'ProfileController@edit_account')->name('edit_account');

Route::patch('/account/store', 'ProfileController@store_account')->name('store_account');
Route::patch('/profile/store', 'ProfileController@store_profile')->name('store_profile');


Route::post('/acc_check_val', 'ProfileController@acc_check_val')->name('acc_check_val');

Route::patch('/inspect', 'ProfileController@inspect')->name('inspect');
Route::patch('/profile/confirm', 'ProfileController@confirm_profile')->name('confirm_profile');
Route::patch('/account/confirm', 'ProfileController@confirm_account')->name('confirm_account');


Route::patch('/img_upload', 'ProfileController@img_upload')->name('img_upload');

Route::get('/profile/{id}/timeline', 'ProfileController@profile')->name('profile');
Route::get('/profile/{id}/following', 'ProfileController@profile')->name('profile');
Route::get('/profile/{id}/profile', 'ProfileController@profile')->name('profile');
Route::get('/profile/{id}', 'ProfileController@profile')->name('profile');

//Route::get('/follow_list', 'ProfileController@get_follow_list')->name('get_follow_list');
Route::post('/follow_user', 'ProfileController@follow_user')->name('follow_user');
Route::post('/unfollow_user', 'ProfileController@unfollow_user')->name('unfollow_user');


// login
Route::get('/', 'LoginController@login')->name('login');

Route::get('/login', 'LoginController@login')->name('login');
Route::post('/login', 'LoginController@login')->name('login');
Route::get('/signup', 'LoginController@signup')->name('signup');
Route::post('/signup', 'LoginController@signup')->name('signup');

Route::get('/logout', 'LoginController@logout')->name('logout');
Route::post('/logout', 'LoginController@logout')->name('logout');

Route::post('/login/validate_login', 'LoginController@validate_login')->name('validate_login');
Route::post('/login/validate_signup', 'LoginController@validate_signup')->name('validate_signup');

Route::post('/signup/confirm', 'LoginController@confirm_signup')->name('confirm_signup');
Route::post('/signup/store', 'LoginController@store_signup')->name('store_signup');
















// test
Route::get('/error', 'TweetController@error')->name('error');
Route::get('/test_view', 'TweetController@test_view')->name('test_view');
Route::post('/test_ajax', 'TweetController@test_ajax')->name('test_ajax');




