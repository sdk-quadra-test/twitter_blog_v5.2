<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Http\Requests;
use DB;
use App\User;
use App\Follow;
use App\Tweet;
//# 自力validate用
use Validator;

class LoginController extends Controller
{

    ##########################################
    # view
    ##########################################
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

    public function signup(){
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
        $rules = [
            'name' => 'required|max:16',
            'password' => 'required|max:50',
        ];

        $messages = [
            'name.required' => 'ユーザー名を入力してください',
            'name.max' => 'ユーザー名は :max 文字以内で入力して下さい',
            'password.required' => 'パスワードを入力してください',
            'password.max' => 'パスワードは :max 文字以内で入力して下さい',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

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
//            $a = 1;
//            dd($a);
            $back_with_error = $this->back_with_error($validator);
            return $back_with_error;
        }

//        if ($validator->fails()) {
//            return redirect()->back()
//                ->withErrors($validator)
//                ->withInput();
//        }
//        if ($validator->passes()) {
//            $this->store_user($request);
//            return redirect('/login');
//        }

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
        $rand_num = rand(1,30);

        $default_icon_url = $aws_url.'/anonymous/anonymous_'.$rand_num.'.png';

        $user = new User;
        $user->name = $name;
        $user->disp_name = $disp_name;
        $user->password = $pass;
        $user->icon_url = $default_icon_url;
        $user->save();

        # 新規登録者はdefaultでuser1をfollow
        $follow = new Follow;
        $follow->user_id = $user->id;
        $follow->following_user_id = 1;
        $follow->save();

        # 新規登録者はdefaultで参加をtweet
        $tweet = new Tweet;
        $tweet->user_id = $user->id;
        $tweet->content = $user->name.' (@'. $user->name .')'.'がツイートプログラムに参加しました';
        $tweet->save();


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

        $exist_user = $this->get_exist_user($name, $pass);
        $user = $this->get_user_by_name($name);

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
        $rules = [
//            'name' => 'required|max:16|unique:user,name,' . $sess_user_id,
            'name' => 'required|max:16|unique:user',
            'disp_name' => 'required|max:20',
            'password' => 'required|max:50',
            'confirm_password' => 'required|max:50|same:password',
        ];

        $messages = [
            'name.required' => 'ユーザー名を入力してください',
            'name.max' => 'ユーザー名は :max 文字以内で入力して下さい',
            'disp_name.required' => '表示名を入力してください',
            'disp_name.max' => '表示名は :max 文字以内で入力して下さい',
            'name.unique' => 'そのユーザー名は既に登録されています。別のユーザー名で登録して下さい',
            'password.required' => 'パスワードを入力してください',
            'password.max' => 'パスワードは :max 文字以内で入力して下さい',
            'confirm_password.required' => 'パスワード確認を入力してください',
            'confirm_password.max' => 'パスワード確認は :max 文字以内で入力して下さい',
            'confirm_password.same' => 'パスワード確認が一致しません',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return $validator;
        }
//        if ($validator->passes()) {
//        }
        return null;

    }

    private function back_with_error($validator)
    {
        return redirect()->back()
            ->withErrors($validator)
            ->withInput();
    }

    private function get_exist_user($name, $pass)
    {
        $exist_user = DB::table('user')
            ->where('name', $name)
            ->where('password', $pass)
            ->where('is_deleted', 0)
            ->first();
        return $exist_user;
    }

    private function get_user_by_name($name)
    {
        $user = DB::table('user')
            ->select('id','name','disp_name')
            ->where('name', $name)
            ->where('is_deleted', 0)
            ->first();
        return $user;
    }

}
