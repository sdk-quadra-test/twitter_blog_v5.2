<?php

namespace App\Http\Controllers\Concern;

use DB;

class CommonTwitter
{

//    public static function get_all_user_name()
//    {
//        $all_user_name = DB::table('user')
//            ->select('id', 'name')
//            ->where('is_deleted', 0)
//            ->get();
//        $arr_all_user_name = [];
//        foreach ($all_user_name as $k => $v) {
//            $arr_all_user_name = $arr_all_user_name + array($v->id => $v->name);
//        }
//
//        return $arr_all_user_name;
//    }
//
//    public static function get_all_user_disp_name()
//    {
//        $all_user_disp_name = DB::table('user')
//            ->select('id', 'disp_name')
//            ->where('is_deleted', 0)
//            ->get();
//        $arr_all_user_disp_name = [];
//        foreach ($all_user_disp_name as $k => $v) {
//            $arr_all_user_disp_name = $arr_all_user_disp_name + array($v->id => $v->disp_name);
//        }
//        return $arr_all_user_disp_name;
//    }

//    public static function get_user_timeline($id)
//    {
//        $timeline = DB::table('tweet')
//            ->join('user', 'tweet.user_id', '=', 'user.id')
//            ->select('tweet.user_id', 'icon_url', 'disp_name', 'name', 'content', 'tweet.created_at as tweet_created_at')
//            ->where('user.id', $id)
//            ->where('tweet.is_deleted', 0)
//            ->where('user.is_deleted', 0)
//            ->orderBy('tweet.created_at', 'desc')
//            ->paginate(10);
//        return $timeline;
//    }

//    public static function get_following_user_id($id)
//    {
//        $following_user_id = DB::table('follow')
//            ->select('user_id', 'following_user_id')
//            ->where('user_id', $id)
//            ->where('is_unfollowed', 0)
//            ->where('is_deleted', 0)
//            ->get();
//        return $following_user_id;
//    }


}
