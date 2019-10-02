<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use App\Http\Controllers\UserController;
use App\User;
use App\Follow;
use DB;


// 共通クラス
use App\Http\Controllers\Concern\CommonTwitter;

class UserProfileController extends Controller
{
    public function profile($id)
    {
        $profile = self::get_user_profile($id);

        if (count($profile) < 1) {
            abort('404');
        }

        $sess_user_id = session('user_id');
        $follow_count = self::get_follow_count($sess_user_id, $id);
        $timeline = TimelineController::get_user_timeline($id);
        $following_list = FollowingUserController::get_following_list($id);
        $user_tweet_count = TimelineController::count_user_tweet($id);
        $user_follow_count = FollowingUserController::count_user_follow($id);

        $arr_all_user_name = UserController::get_all_user_name();
        //　user名にlink設置
        $timeline = $this->set_link($timeline, $arr_all_user_name);

        return view('twitter.profile')->with([
            'profile' => $profile,
            'timeline' => $timeline,
            'follow_count' => $follow_count,
            'following_list' => $following_list,
            'user_tweet_count' => $user_tweet_count,
            'user_follow_count' => $user_follow_count
        ]);
    }

    private function set_link($timeline, $arr_all_user_name)
    {
        foreach ($timeline as $k => $v) {
            $v->content = htmlspecialchars($v->content);
            foreach ($arr_all_user_name as $id => $name) {
                $v->content = str_replace('@' . $name, "<a href='/profile/{$id}/timeline'>@{$name}</a>", $v->content);
            }
            foreach ($arr_all_user_name as $id => $name) {
                $v->name = str_replace($name, "@{$name}", $v->name);
            }
        }
        return $timeline;
    }

    public static function get_user_profile($id)
    {
        $user_profile = User::get_user_profile($id);
        return $user_profile;
    }

    private static function get_follow_count($sess_user_id, $id)
    {
        $follow_count = Follow::get_follow_count($sess_user_id, $id);
        return $follow_count;
    }


}
