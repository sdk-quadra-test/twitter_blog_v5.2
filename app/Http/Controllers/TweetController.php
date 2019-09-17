<?php

namespace App\Http\Controllers;


use App\Http\Controllers\Concern\CommonTwitter;
use Illuminate\Http\Request;
use App\Http\Requests;
//# 自力validate用
use Validator;
use DB;
use App\Tweet;
use App\Notice;


// 画像upload用
use Storage;

# 画像cache削除用
use Illuminate\Support\Facades\Cache;

class TweetController extends Controller
{

    ##########################################
    # view
    ##########################################

    public function tweet()
    {
        $tweet = new Tweet;

        return view('twitter.timeline', [
            'tweet' => $tweet
        ]);
    }

    public function store_tweet(Request $request)
    {
        if (!session()->has('user_id')) {
            return redirect('/login');
        }
        $sess_user_id = session('user_id');
        $tweet = new Tweet;
        $tweet->user_id = $sess_user_id;
        $tweet->content = $request->content;

        if (strlen($tweet->content) > 1) {
            $tweet->save();
            $this->store_notice($tweet);

        } else {
            $request->session()->flash('err_empty', '1文字以上入力して下さい');
        }
        return redirect('/timeline');
    }

    ##########################################
    # private
    ##########################################

    private function store_notice($tweet)
    {
        $sess_user_id = session('user_id');
        $tweet_id = $tweet->id;
        $content = $tweet->content;

//        $all_user_name = new CommonTwitter();
//        $all_user_name = $all_user_name->get_all_user_name();

        $all_user_name = DB::table('user')
            ->select('id', 'name')
            ->where('is_deleted', 0)
            ->get();

        $arr_user_id = $this->build_user_id_if_exist_user_name($sess_user_id, $all_user_name, $content);

        foreach ($arr_user_id as $k => $to_user_id) {
            $notice = new Notice;
            $notice->tweet_id = $tweet_id;
            $notice->to_user_id = $to_user_id;
            $notice->save();
        }
    }

    private function build_user_id_if_exist_user_name($sess_user_id, $all_user_name, $content)
    {
        $arr_user_id = [];
        foreach ($all_user_name as $k => $v) {
            $name_match = preg_match_all('/@' . $v->name . '/', $content);

            if ($name_match && $v->id !== $sess_user_id) {// 自分から自分へのtweetは除外
                array_push($arr_user_id, $v->id);
            }
        }
        return $arr_user_id;
    }

}


