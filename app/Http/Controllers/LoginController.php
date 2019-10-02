<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
//use App\Http\Controllers\UserController;
use DB;
use App\User;
use App\Follow;
use App\Tweet;

class LoginController extends Controller
{
    public function login()
    {
        //    loginしていたらredirect
        if (session()->has('user_id')) {
            return redirect('/timeline');
        }

        $user = new User;
        return view('twitter.login', [
            'user' => $user
        ]);
    }

    public function signup()
    {
        $user = new User;
        return view('twitter.signup', [
            'user' => $user
        ]);
    }

    public function logout(Request $request)
    {
        $request->session()->forget('user_id');
        $request->session()->forget('name');
        $request->session()->forget('disp_name');
        return redirect('/login');
    }

    public function validate_login(Request $request)
    {
        $validator = User::validate_login($request);
        # viewの兼ね合いで切り出しできない
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        if ($validator->passes()) {
            $this->auth_user($request);
            return redirect('/login');
        }
        return null;
    }

    public function confirm_signup(Request $request)
    {
        $validator = $this->validate_signup($request);

        if ($validator) {
            $back_with_error = $this->back_with_error($validator);
            return $back_with_error;
        }

        $user = new User;
        return view('twitter.signup_confirm')->with([
            'user' => $user,
            'request' => $request
        ]);
    }

    public function store_signup(Request $request)
    {
        $back = $request->back;
        $input = $request->all();
        if ($back == '戻る') {
            return redirect('/signup')->withInput($input);
        }

        $post = $request->all();
        $name = $post['name'];
        $disp_name = $post['disp_name'];
        $pass = $post['password'];

        $aws_url = env('AWS_URL');
        $rand_num = rand(1, 30);
        $default_icon_url = $aws_url . '/anonymous/anonymous_' . $rand_num . '.png';

        $this->store_signup_with_default($name, $disp_name, $pass, $default_icon_url);

        $request->session()->flash('success_signup', '登録成功！ 入力した内容でログインして下さい');
        return redirect('/login');
    }


##########################################
# private
##########################################
    private function auth_user(Request $request)
    {
        $post = $request->all();
        $name = $post['name'];
        $pass = $post['password'];

        $user = UserController::get_user_by_name($name);

        $user_hashed_pass = "";
        if (count($user) > 0) {
            $user_hashed_pass = $user->password;
        }
        $exist_user = $this->verify_pass($name, $pass, $user_hashed_pass);

        if (count($exist_user) > 0) {
            $request->session()->put('user_id', $user->id);
            $request->session()->put('name', $user->name);
            $request->session()->put('disp_name', $user->disp_name);
        } else {
            $request->session()->flash('err_status', 'ユーザー名かパスワードが間違っています');
        }
    }

    private function validate_signup(Request $request)
    {
        $validator = User::validate_signup($request);

        if ($validator->fails()) {
            return $validator;
        }
        return null;
    }

    private function back_with_error($validator)
    {
        return redirect()->back()
            ->withErrors($validator)
            ->withInput();
    }

    private function verify_pass($name, $pass, $user_hashed_pass)
    {
        $pass_verify = password_verify($pass, $user_hashed_pass);

        if ($pass_verify) {
            $exist_user = User::get_user_by_name($name);
            return $exist_user;
        }
        return null;
    }

    private function store_signup_with_default($name, $disp_name, $pass, $default_icon_url)
    {
        $user = new User;
        $user->store_signup_with_default($name, $disp_name, $pass, $default_icon_url);

    }

}
