<?php

namespace App;

use App\Http\Controllers\NoticeController;
use Illuminate\Database\Eloquent\Model;
use DB;

//use App\Traits\IsDeleted;

class Tweet extends Model
{
//    use IsDeleted;

    protected $table = 'tweet';

    public static function get_all_timeline()
    {
        return self::query()
            ->join('user', 'tweet.user_id', '=', 'user.id')
            ->select('tweet.user_id', 'icon_url', 'disp_name', 'name', 'content', 'tweet.created_at as tweet_created_at')
            ->where('tweet.is_deleted', 0)
            ->where('user.is_deleted', 0)
            ->orderBy('tweet.created_at', 'desc')
            ->paginate(20);
    }

    public static function get_loggedin_timeline($sess_user_id)
    {
        return self::query()
            ->join('user', 'tweet.user_id', '=', 'user.id')
            ->select('user.id', 'tweet.user_id', 'user.icon_url', 'user.disp_name', 'user.name', 'tweet.content', 'tweet.created_at as tweet_created_at')
            ->where('tweet.user_id', $sess_user_id)
            ->whereIn('tweet.user_id', function ($query) use ($sess_user_id) {
                $query->select('following_user_id')->from('follow')
                    ->where('user_id', $sess_user_id)
                    ->where('is_unfollowed', 0)
                    ->where('is_deleted', 0);
            }, 'or')
            ->where('tweet.is_deleted', 0)
            ->where('user.is_deleted', 0)
            ->orderBy('tweet.created_at', 'desc')
            ->paginate(20);
    }

    public static function get_user_timeline($id)
    {
        return self::query()
            ->join('user', 'tweet.user_id', '=', 'user.id')
            ->select('tweet.user_id', 'icon_url', 'disp_name', 'name', 'content', 'tweet.created_at as tweet_created_at')
            ->where('user.id', $id)
            ->where('tweet.is_deleted', 0)
            ->where('user.is_deleted', 0)
            ->orderBy('tweet.created_at', 'desc')
            ->paginate(10);
    }

    public static function count_user_tweet($id)
    {
        return self::query()
            ->select('id')
            ->where('user_id', $id)
            ->where('is_deleted', 0)
            ->count();
    }

    public static function exist_following_notice($user, $following_user_id)
    {
        return self::query()
            ->join('notice', 'tweet.id', '=', 'notice.tweet_id')
            ->where('user_id', $user->id)
            ->where('to_user_id', $following_user_id)
            ->get();
    }

    public function store_tweet($request, $sess_user_id)
    {
        return DB::transaction(function () use ($request, $sess_user_id) {
            $tweet = new Tweet;
            $tweet->user_id = $sess_user_id;
            $tweet->content = $request->content;

            if (strlen($tweet->content) >= 1) {
                $tweet->save();
                $notice_controller = new NoticeController();
                $notice_controller->store_notice($tweet);
            } else {
                $request->session()->flash('err_empty', '1文字以上入力して下さい');
            }
        });
    }

    public function push_notice_to_user($user, $follow_user_profile, $following_user_id)
    {
        return DB::transaction(function () use ($user, $follow_user_profile, $following_user_id) {
            $tweet = new Tweet;
            $tweet->user_id = $user->id;
            $tweet->content = $user->disp_name . ' (@' . $user->name . ')' . 'が' .
                $follow_user_profile->disp_name . 'をフォローしました';
            $tweet->save();

            $notice = new Notice;
            $notice->tweet_id = $tweet->id;
            $notice->to_user_id = $following_user_id;
            $notice->save();
        });
    }
}
