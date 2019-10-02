<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use App\Http\Controllers\UserController;
use App\Notice;
use App\Tweet;
use App\Follow;
use Illuminate\Http\Request;
use DB;

//# 自力validate用


class FollowController extends Controller
{
    public function follow_user(Request $request)
    {
        if (!session()->has('user_id')) {
            return redirect('/login');
        }
        $sess_user_id = session('user_id');
        $request = $request->all();
        $following_user_id = $request['following_user_id'];
        $exist_following_user = $this->exist_following_user($sess_user_id, $following_user_id);

        if (count($exist_following_user) < 1) {
            $this->follow_user_first_time($sess_user_id, $following_user_id);
            $this->push_notice_to_user($following_user_id);

        } # unfollow解除
        elseif (count($exist_following_user) >= 1 && $exist_following_user->is_unfollowed == 1) {
            $this->follow_user_not_first_time($sess_user_id, $following_user_id);
            $this->push_notice_to_user($following_user_id);
        }
        return null;
    }

    public function unfollow_user(Request $request)
    {
        if (!session()->has('user_id')) {
            return redirect('/login');
        }
        $sess_user_id = session('user_id');
        $request = $request->all();
        $following_user_id = $request['following_user_id'];
        $exist_following_user = $this->exist_following_user($sess_user_id, $following_user_id);

//        dataがあれば,そのrecordのunfollowに1
        if (count($exist_following_user) >= 1) {
            $this->unfollow_user_anytime($sess_user_id, $following_user_id);
        }
        return null;
    }

    private function push_notice_to_user($following_user_id)
    {
        $user = UserController::get_loggedin_user();
        $follow_user_profile = UserProfileController::get_user_profile($following_user_id);
        $exist_following_notice = Tweet::exist_following_notice($user, $following_user_id);

        if (count($exist_following_notice) < 1) {
            $push_notice_to_user = new Tweet;
            $push_notice_to_user->push_notice_to_user($user, $follow_user_profile, $following_user_id);
        }
        return null;
    }

    private function exist_following_user($sess_user_id, $following_user_id)
    {
        $exist_following_user = Follow::exist_following_user($sess_user_id, $following_user_id);
        return $exist_following_user;
    }

    private function follow_user_first_time($sess_user_id, $following_user_id)
    {
        $follow_user_first_time = new Follow;
        $follow_user_first_time->follow_user_first_time($sess_user_id, $following_user_id);
    }

    private function follow_user_not_first_time($sess_user_id, $following_user_id)
    {
        $follow = Follow::exist_following_user($sess_user_id, $following_user_id);
        $follow_user_not_first_time = new Follow;
        $follow_user_not_first_time->follow_user_not_first_time($follow);
    }

    private function unfollow_user_anytime($sess_user_id, $following_user_id)
    {
        $unfollow = Follow::exist_following_user($sess_user_id, $following_user_id);
        $unfollow_user_anytime = new Follow;
        $unfollow_user_anytime->unfollow_user_anytime($unfollow);
    }

}
