<?php

namespace App\Http\Controllers\Profile;
use App\Http\Controllers\Controller;
use App\User;
use App\Follow;
use DB;

class FollowingUserController extends Controller
{

    public static function get_following_list($id)
    {
        $following_user_id = Follow::get_following_user_id($id);
        $arr_user_id = [];
        foreach ($following_user_id as $k => $v) {
            array_push($arr_user_id, $v->following_user_id);
        }
        $following_list = User::get_user_list($arr_user_id);
        return $following_list;
    }

    public static function count_user_follow($id)
    {
        $user_follow_count = Follow::count_user_follow($id);
        return $user_follow_count;
    }

}
