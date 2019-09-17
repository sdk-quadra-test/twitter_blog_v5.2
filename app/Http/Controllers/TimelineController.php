<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concern\CommonTwitter;
use Illuminate\Http\Request;
use App\Http\Requests;
use DB;

class TimelineController extends Controller
{
    ##########################################
    # view
    ##########################################
    public function timeline()
    {
        //  全件表示
        $timeline = $this->get_all_timeline();

        //  ログイン時表示
        //  自分のtweet + followしているuserのtweet
        if (session()->has('user_id')) {
            $sess_user_id = session('user_id');
            $timeline = $this->get_loggedin_timeline($sess_user_id);
        }

        //　user名にlink設置
        $arr_all_user_name = new CommonTwitter();
        $arr_all_user_name = $arr_all_user_name->get_all_user_name();

        $arr_all_user_disp_name = new CommonTwitter();
        $arr_all_user_disp_name = $arr_all_user_disp_name->get_all_user_disp_name();

        foreach ($timeline as $k => $v) {
            $v->content = htmlspecialchars($v->content);

            foreach ($arr_all_user_name as $id => $name) {
                $v->content = str_replace('@' . $name, "<a href='/profile/{$id}/timeline'>@{$name}</a>", $v->content);
            }
            foreach ($arr_all_user_name as $id => $name) {
                $v->name = str_replace($name, "<a href='/profile/{$id}/timeline'>@{$name}</a>", $v->name);
            }
            foreach ($arr_all_user_disp_name as $id => $disp_name) {
                $v->disp_name = str_replace($disp_name, "<a href='/profile/{$id}/timeline'>{$disp_name}</a>", $v->disp_name);
            }
        }

        return view('twitter.timeline',
            ['timeline' => $timeline]);
    }


    ##########################################
    # private
    ##########################################
    private function get_all_timeline()
    {
        $timeline = DB::table('tweet')
            ->join('user', 'tweet.user_id', '=', 'user.id')
            ->select('tweet.user_id', 'icon_url', 'disp_name', 'name', 'content', 'tweet.created_at as tweet_created_at')
            ->where('tweet.is_deleted', 0)
            ->where('user.is_deleted', 0)
            ->orderBy('tweet.created_at', 'desc')
            ->paginate(20);

        return $timeline;
    }

    private function get_loggedin_timeline($sess_user_id)
    {
        $following_user_id = new CommonTwitter();
        $following_user_id = $following_user_id->get_following_user_id($sess_user_id);

        $arr_user_id = [];
        foreach ($following_user_id as $k => $v) {
            array_push($arr_user_id, $v->following_user_id);
        }

        // 自分を加える
        array_push($arr_user_id, $sess_user_id);

        $timeline = DB::table('tweet')
            ->join('user', 'tweet.user_id', '=', 'user.id')
            ->select('tweet.user_id', 'icon_url', 'disp_name', 'name', 'content', 'tweet.created_at as tweet_created_at')
            ->whereIn('user.id', $arr_user_id)
            ->where('tweet.is_deleted', 0)
            ->where('user.is_deleted', 0)
            ->orderBy('tweet.created_at', 'desc')
            ->paginate(20);

        return $timeline;
    }

}
