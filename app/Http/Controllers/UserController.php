<?php

namespace App\Http\Controllers;

use DB;
use App\User;

class UserController extends Controller
{

    public function get_all_user()
    {
        $user = User::get_all_user();
        return view('twitter.user', [
            'user' => $user
        ]);
    }

    public static function get_all_user_name()
    {
        $all_user_name = User::get_all_user_name();
        $arr_all_user_name = [];
        foreach ($all_user_name as $k => $v) {
            $arr_all_user_name = $arr_all_user_name + array($v->id => $v->name);
        }
        return $arr_all_user_name;
    }

    public static function get_all_user_disp_name()
    {
        $all_user_disp_name = User::get_all_user_disp_name();
        $arr_all_user_disp_name = [];
        foreach ($all_user_disp_name as $k => $v) {
            $arr_all_user_disp_name = $arr_all_user_disp_name + array($v->id => $v->disp_name);
        }
        return $arr_all_user_disp_name;
    }

    public static function get_user_by_name($name)
    {
        $user = User::get_user_by_name($name);
        return $user;
    }

    public static function get_loggedin_user()
    {
        if (!session()->has('user_id')) {
            return redirect('/login');
        }

        $sess_user_id = session('user_id');
        $loggedin_user = User::get_loggedin_user($sess_user_id);
        return $loggedin_user;
    }

}
