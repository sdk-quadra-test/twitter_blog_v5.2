<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Tweet;

class TweetController extends Controller
{
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
        $store_tweet = new Tweet();
        $store_tweet->store_tweet($request, $sess_user_id);
        return redirect('/timeline');
    }

}


