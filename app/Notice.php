<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

//use App\Traits\IsDeleted;

class Notice extends Model
{
//    use IsDeleted;

    protected $table = 'notice';

    public static function not_read_yet($sess_user_id)
    {
        return self::query()
            ->where('to_user_id', $sess_user_id)
            ->where('is_read', 0)
            ->where('is_deleted', 0)
            ->get();
    }

    public static function get_notice($sess_user_id)
    {
        return self::query()
            ->join('tweet', 'notice.tweet_id', '=', 'tweet.id')
            ->join('user', 'tweet.user_id', '=', 'user.id')
            ->select('tweet.user_id', 'user.name', 'user.disp_name', 'user.icon_url', 'tweet.content', 'notice.is_read', 'tweet.created_at as tweet_created_at')
            ->where('notice.to_user_id', $sess_user_id)
            ->where('tweet.user_id', '!=', $sess_user_id)
            ->where('notice.is_deleted', 0)
            ->where('tweet.is_deleted', 0)
            ->where('user.is_deleted', 0)
            ->orderBy('tweet.created_at', 'desc')
            ->paginate(20);
    }

    public static function count_is_read($sess_user_id)
    {
        return self::query()
            ->where('to_user_id', $sess_user_id)
            ->where('is_read', 0)
            ->where('is_deleted', 0)
            ->count();
    }

    public static function update_to_read($not_read_yet, $is_read)
    {
        return DB::transaction(function () use ($not_read_yet, $is_read) {
            foreach ($not_read_yet as $k => $v) {
                $v->is_read = $is_read;
                $v->save();
            }
        });
    }

    public function store_notice($arr_user_id, $tweet_id)
    {
        return DB::transaction(function () use ($arr_user_id, $tweet_id) {
            foreach ($arr_user_id as $k => $to_user_id) {
                $notice = new Notice;
                $notice->tweet_id = $tweet_id;
                $notice->to_user_id = $to_user_id;
                $notice->save();
            }
        });
    }

}
