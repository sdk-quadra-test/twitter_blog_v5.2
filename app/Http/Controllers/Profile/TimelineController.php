<?php
namespace App\Http\Controllers\Profile;
use App\Http\Controllers\Controller;
use App\Tweet;
use DB;

class TimelineController extends Controller
{
    public static function count_user_tweet($id)
    {
        $user_tweet_count = Tweet::count_user_tweet($id);
        return $user_tweet_count;
    }

    public static function get_user_timeline($id)
    {
        $timeline = Tweet::get_user_timeline($id);
        return $timeline;
    }
}
