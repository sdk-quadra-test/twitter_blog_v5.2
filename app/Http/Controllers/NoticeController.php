<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concern\CommonTwitter;
use App\Notice;
use Illuminate\Http\Request;
use DB;
use App\Http\Requests;

class NoticeController extends Controller
{

    ##########################################
    # view
    ##########################################

    public function notice()
    {
        if (!session()->has('user_id')) {
            return redirect('/login');
        }

        $sess_user_id = session('user_id');
        $notice = $this->get_notice($sess_user_id);

        //　user名にlink設置
        $arr_all_user_name = new CommonTwitter();
        $arr_all_user_name = $arr_all_user_name->get_all_user_name();

        $arr_all_user_disp_name = new CommonTwitter();
        $arr_all_user_disp_name = $arr_all_user_disp_name->get_all_user_disp_name();

        foreach ($notice as $k => $v) {
            $v->content = htmlspecialchars($v->content);
            foreach ($arr_all_user_name as $id => $name) {
                $v->name = str_replace($name, "<a href='/profile/{$id}/timeline'>@{$name}</a>", $v->name);
            }
            foreach ($arr_all_user_disp_name as $id => $disp_name) {
                $v->disp_name = str_replace($disp_name, "<a href='/profile/{$id}/timeline'>{$disp_name}</a>", $v->disp_name);
            }
        }

        return view('twitter.notice', [
            'notice' => $notice
        ]);
    }

    public function count_notice()
    {
        if (!session()->has('user_id')) {
            return redirect('/login');
        }

        $sess_user_id = session('user_id');
        $count_notice = $this->get_is_read($sess_user_id);
        return $count_notice;
    }

    public function update_to_read(Request $request)
    {
        if (!session()->has('user_id')) {
            return redirect('/login');
        }

        $sess_user_id = session('user_id');
        $request = $request->all();
        $is_read = $request['is_read'];

        $not_read = Notice::where('to_user_id', $sess_user_id)
            ->where('is_read', 0)
            ->where('is_deleted', 0)
            ->get();

        if (count($not_read) >= 1) {
            foreach ($not_read as $k => $v) {
                $v->is_read = $is_read;
                $v->save();
            }
        }
    }


    ##########################################
    # private
    ##########################################

    private function get_notice($sess_user_id){
        $notice = DB::table('notice')
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
        return $notice;
    }

    private function get_is_read($sess_user_id){
        $count_notice = DB::table('notice')
            ->where('to_user_id', $sess_user_id)
            ->where('is_read', 0)
            ->where('is_deleted', 0)
            ->count();
        return $count_notice;
    }


}
