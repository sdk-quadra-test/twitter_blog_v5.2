<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

//use App\Traits\IsDeleted;

class Follow extends Model
{
//    use IsDeleted;

    protected $table = 'follow';

    public static function get_follow_count($sess_user_id, $id)
    {
        return self::query()
            ->select('user_id', 'following_user_id')
            ->where('user_id', $sess_user_id)
            ->where('following_user_id', $id)
            ->where('is_unfollowed', 0)
            ->where('is_deleted', 0)
            ->count();
    }

    public static function count_user_follow($id)
    {
        return self::query()
            ->select('id')
            ->where('user_id', $id)
            ->where('is_unfollowed', 0)
            ->where('is_deleted', 0)
            ->count();
    }

    public static function get_following_user_id($id)
    {
        return self::query()
            ->select('user_id', 'following_user_id')
            ->where('user_id', $id)
            ->where('is_unfollowed', 0)
            ->where('is_deleted', 0)
            ->get();
    }

    public static function exist_following_user($sess_user_id, $following_user_id)
    {
        return self::query()
            ->where('user_id', $sess_user_id)
            ->where('following_user_id', $following_user_id)
            ->where('is_deleted', 0)
            ->first();
    }

    public function follow_user_first_time($sess_user_id, $following_user_id)
    {
        return DB::transaction(function () use ($sess_user_id, $following_user_id) {
            $follow = new Follow;
            $follow->user_id = $sess_user_id;
            $follow->following_user_id = $following_user_id;
            $follow->save();
        });
    }

    public function follow_user_not_first_time($follow)
    {
        return DB::transaction(function () use ($follow) {
            $follow->is_unfollowed = 0;
            $follow->save();
        });
    }

    public function unfollow_user_anytime($unfollow)
    {
        return DB::transaction(function () use ($unfollow) {
            $unfollow->is_unfollowed = 1;
            $unfollow->save();
        });
    }

}
