<?php

namespace App\Http\Controllers;

//use App\Http\Controllers\UserController;
use App\User;
use App\Notice;
use Illuminate\Http\Request;
use DB;
//use Mockery\Matcher\Not;

class NoticeController extends Controller
{
    public function notice()
    {
        if (!session()->has('user_id')) {
            return redirect('/login');
        }

        $sess_user_id = session('user_id');
        $notice = $this->get_notice($sess_user_id);

        //　user名にlink設置
        $arr_all_user_name = UserController::get_all_user_name();
        $arr_all_user_disp_name = UserController::get_all_user_disp_name();

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
        $count_notice = $this->count_is_read($sess_user_id);
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

        $not_read_yet = Notice::not_read_yet($sess_user_id);

        if (count($not_read_yet) >= 1) {
            Notice::update_to_read($not_read_yet, $is_read);
        }
        return null;
    }

    public function store_notice($tweet)
    {
        $sess_user_id = session('user_id');
        $tweet_id = $tweet->id;
        $content = $tweet->content;

        $all_user_name = User::get_all_user_name();

        $arr_user_id = $this->build_user_id_if_exist_user_name($sess_user_id, $all_user_name, $content);

        if (count($arr_user_id) > 0) {
            $store_notice = new Notice();
            $store_notice->store_notice($arr_user_id, $tweet_id);
        }
        return null;
    }

    public function build_user_id_if_exist_user_name($sess_user_id, $all_user_name, $content)
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


##########################################
# private
##########################################

    private function get_notice($sess_user_id)
    {
        $notice = Notice::get_notice($sess_user_id);
        return $notice;
    }

    private function count_is_read($sess_user_id)
    {
        $count_notice = Notice::count_is_read($sess_user_id);
        return $count_notice;
    }
}
