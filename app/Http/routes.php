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
Route::get('/profile/edit', 'Profile\ProfileEditController@edit_profile')->name('edit_profile');
Route::post('/profile/edit', 'Profile\ProfileEditController@edit_profile')->name('edit_profile');
Route::patch('/profile/confirm', 'Profile\ProfileEditController@confirm_profile')->name('confirm_profile');
Route::patch('/profile/store', 'Profile\ProfileEditController@store_profile')->name('store_profile');

Route::get('/account/edit', 'Profile\AccountEditController@edit_account')->name('edit_account');
Route::patch('/account/edit', 'Profile\AccountEditController@edit_account')->name('edit_account');
Route::patch('/account/confirm', 'Profile\AccountEditController@confirm_account')->name('confirm_account');
Route::patch('/account/store', 'Profile\AccountEditController@store_account')->name('store_account');

Route::get('/profile/{id}/timeline', 'Profile\UserProfileController@profile')->name('profile');
Route::get('/profile/{id}/following', 'Profile\UserProfileController@profile')->name('profile');
Route::get('/profile/{id}/profile', 'Profile\UserProfileController@profile')->name('profile');
Route::get('/profile/{id}', 'Profile\UserProfileController@profile')->name('profile');

Route::post('/follow_user', 'Profile\FollowController@follow_user')->name('follow_user');
Route::post('/unfollow_user', 'Profile\FollowController@unfollow_user')->name('unfollow_user');


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
