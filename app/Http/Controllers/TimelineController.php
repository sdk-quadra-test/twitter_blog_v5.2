<?php

namespace App\Http\Controllers;

//use App\Http\Controllers\UserController;

use DB;
use App\Tweet;


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
        foreach ($timeline as $v) {

            $v->name = str_replace($v->name, "<a href='/profile/{$v->id}/timeline'>@{$v->name}</a>", $v->name);
            $v->disp_name = str_replace($v->disp_name, "<a href='/profile/{$v->id}/timeline'>{$v->disp_name}</a>", $v->disp_name);

            # content処理
            # contentにlink設置
            $v->content = htmlspecialchars($v->content);
            $preg_match_all = preg_match_all('/@([A-Za-z0-9]+)/', $v->content, $match);
            if ($preg_match_all) {

                # 先頭削除
                array_shift($match);
                $this->set_link_to_content($v, $match);
            }
        }

        return view('twitter.timeline',
            ['timeline' => $timeline]);
    }

    ##########################################
    # private
    ##########################################

    private function set_link_to_content($v, $match)
    {
        foreach ($match as $arr) {
            foreach ($arr as $name) {
                $exist_user = UserController::get_user_by_name($name);
                if ($exist_user) {
                    $user_id = $exist_user->id;
                    $v->content = str_replace('@' . $name, "<a href='/profile/{$user_id}/timeline'>@{$name}</a>", $v->content);
                }
            }
        }
    }

    private function get_all_timeline()
    {
        $timeline = Tweet::get_all_timeline();
        return $timeline;
    }

    private function get_loggedin_timeline($sess_user_id)
    {
        $timeline = Tweet::get_loggedin_timeline($sess_user_id);
        return $timeline;
    }

}
