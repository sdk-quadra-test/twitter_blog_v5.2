<?php

namespace App\Http\Controllers;

use App\User;
use App\Follow;
use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades\Cache;
use DB;
//# 自力validate用
use Validator;
// 画像upload用
use Storage;


// 共通クラス
use App\Http\Controllers\Concern\CommonTwitter;

class ProfileController extends Controller
{


    ##########################################
    # view
    ##########################################
    public function profile($id)
    {
//        $follow_count = null;
//        if (!session()->has('user_id')) {
//            return redirect('/login');
//        }


        $sess_user_id = session('user_id');

        $follow_count = $this->get_follow_count($sess_user_id, $id);

        $profile = $this->get_user_profile($id);

        $timeline = new CommonTwitter();
        $timeline = $timeline->get_user_timeline($id);

        $following_list = $this->get_following_list($id);

        $user_tweet_count = $this->get_user_tweet_count($id);
        $user_follow_count = $this->get_user_follow_count($id);

        //　user名にlink設置
        $arr_all_user_name = new CommonTwitter();
        $arr_all_user_name = $arr_all_user_name->get_all_user_name();

        foreach ($timeline as $k => $v) {
            $v->content = htmlspecialchars($v->content);
            foreach ($arr_all_user_name as $id => $name) {
                $v->content = str_replace('@' . $name, "<a href='/profile/{$id}/timeline'>@{$name}</a>", $v->content);
            }
            foreach ($arr_all_user_name as $id => $name) {
                $v->name = str_replace($name, "@{$name}", $v->name);
            }
        }


        return view('twitter.profile')->with([
            'profile' => $profile,
            'timeline' => $timeline,
            'follow_count' => $follow_count,
            'following_list' => $following_list,
            'user_tweet_count' => $user_tweet_count,
            'user_follow_count' => $user_follow_count
        ]);
    }


    public function edit_profile()
    {
        if (!session()->has('user_id')) {
            return redirect('/login');
        }
        $user = $this->get_loggedin_user();
        return view('twitter.profile_edit', [
            'user' => $user
        ]);
    }

    public function store_profile(Request $request)
    {
        if (!session()->has('user_id')) {
            return redirect('/login');
        }

        $sess_user_id = session('user_id');
        $back = $request->back;
        $input = $request->all();

        if ($back == '戻る') {
            return redirect('/profile/edit')->withInput($input);
        }

        # iconがアップされていたら
        # tmp file読み込み
        if ($request->icon_url['size'] > 0) {
            $ext = $this->get_ext($request);
            $fopen_tmp = $this->get_tmp_file($request, $sess_user_id);
        }
        if (session()->has('user_id')) {
            $sess_user_id = session('user_id');
            $user = User::find($sess_user_id);
            $user->profile = $request->profile;

            # iconがアップされていたら
            if ($request->icon_url['size'] > 0) {
                $aws_url = env('AWS_URL');
                $file_name = 'icon_' . $sess_user_id . '.' . $ext;
                $this->put_tmp_file_to_s3($file_name, $fopen_tmp);
                $user->icon_url = $aws_url . '/' . $file_name;
            }
            $user->save();
            Cache::forget($user->icon_url);
            return redirect('/profile/' . $sess_user_id . '/timeline');
        }
        return null;
    }

    public function get_following_list($id)
    {
        $following_user_id = new CommonTwitter();
        $following_user_id = $following_user_id->get_following_user_id($id);

        $arr_user_id = [];
        foreach ($following_user_id as $k => $v) {
            array_push($arr_user_id, $v->following_user_id);
        }
        $following_list = $this->get_user_list($arr_user_id);
        return $following_list;
    }

//    public function get_follow_list()
//    {
//        if (!session()->has('user_id')) {
//            return redirect('/timeline');
//        }
//
//        $sess_user_id = session('user_id');
//        $following_user_id = new CommonTwitter();
//        $following_user_id = $following_user_id->get_following_user_id($sess_user_id);
//
//        $arr_user_id = [];
//        foreach ($following_user_id as $k => $v) {
//            array_push($arr_user_id, $v->following_user_id);
//        }
//
//        $following_list = $this->get_user_list($arr_user_id);
//        $user = $this->get_loggedin_user();
//
//        return view('twitter.follow_list')->with([
//            'user' => $user,
//            'following_list' => $following_list
//        ]);
//    }

    public function confirm_profile(Request $request)
    {
        if (!session()->has('user_id')) {
            return redirect('/login');
        }

        $validator = $this->validate_profile($request);

        if ($validator) {
            $back_with_error = $this->back_with_error($validator);
            return $back_with_error;
        }

        # iconがアップされたら
        if ($_FILES['icon_url']['size'] > 0) {
            /*tmp imgをmoveして一時フォルダに保存*/
            $icon_name = 'icon_' . $sess_user_id = session('user_id') . '.' . $request->file('icon_url')->guessExtension(); // TMPファイル名
            $request->file('icon_url')->move(public_path() . "/img/tmp/", $icon_name);

            $tmp_icon_url = "/img/tmp/" . $icon_name;
        } else {
            $tmp_icon_url = "";
        }

//        $validator = $this->validate_profile($request);
//
//        if ($validator) {
//            $back_with_error = $this->back_with_error($validator);
//            return $back_with_error;
//        }

        $user = $this->get_loggedin_user();

        return view('twitter.profile_confirm')->with([
            'user' => $user,
            'request' => $request,
            'tmp_icon_url' => $tmp_icon_url
        ]);
    }

    public function confirm_account(Request $request)
    {
        if (!session()->has('user_id')) {
            return redirect('/login');
        }

        $validator = $this->validate_account($request);

        if ($validator) {
            $back_with_error = $this->back_with_error($validator);
            return $back_with_error;
        }

        $user = $this->get_loggedin_user();

        return view('twitter.account_confirm')->with([
            'user' => $user,
            'request' => $request
        ]);
    }

    public function edit_account()
    {
        if (!session()->has('user_id')) {
            return redirect('/login');
        }

        $user = $this->get_loggedin_user();
        return view('twitter.account_edit', [
            'user' => $user
        ]);
    }

    public function store_account(Request $request)
    {
        if (!session()->has('user_id')) {
            return redirect('/login');
        }

        $sess_user_id = session('user_id');
        $back = $request->back;
        $input = $request->all();

        if ($back == '戻る') {
            return redirect('/account/edit')->withInput($input);
        }

        if (session()->has('user_id')) {
            $sess_user_id = session('user_id');
            $user = User::find($sess_user_id);
            $user->name = $request->name;
            $user->disp_name = $request->disp_name;
            $user->password = $request->password;
            $user->save();
        }
        return redirect('/profile/' . $sess_user_id . '/timeline');
    }

    public function follow_user(Request $request)
    {
        $sess_user_id = session('user_id');
        $request = $request->all();
        $following_user_id = $request['following_user_id'];
        $exist_following_user = $this->exist_following_user($sess_user_id, $following_user_id);

        if (count($exist_following_user) < 1) {
            $follow = new Follow;
            $follow->user_id = $sess_user_id;
            $follow->following_user_id = $following_user_id;
            $follow->save();
        } # unfollow解除
        elseif (count($exist_following_user) >= 1 && $exist_following_user->is_unfollowed == 1) {
            $follow = Follow::where('user_id', $sess_user_id)
                ->where('following_user_id', $following_user_id)
                ->where('is_deleted', 0)
                ->first();
            $follow->is_unfollowed = 0;
            $follow->save();
        }
    }

    public function unfollow_user(Request $request)
    {
        $sess_user_id = session('user_id');

        $request = $request->all();
        $following_user_id = $request['following_user_id'];
        $exist_following_user = $this->exist_following_user($sess_user_id, $following_user_id);

//        dataがあれば,そのrecordのunfollowに1
        if (count($exist_following_user) >= 1) {
            $unfollow = Follow::where('user_id', $sess_user_id)
                ->where('following_user_id', $following_user_id)
                ->where('is_deleted', 0)
                ->first();
            $unfollow->is_unfollowed = 1;
            $unfollow->save();
        }
    }


    ####################################
    # private
    ####################################

    private function get_user_tweet_count($id)
    {
        $user_tweet_count = DB::table('tweet')
            ->select('id')
            ->where('user_id', $id)
            ->where('is_deleted', 0)
            ->count();
        return $user_tweet_count;
    }

    private function get_user_follow_count($id)
    {
        $user_follow_count = DB::table('follow')
            ->select('id')
            ->where('user_id', $id)
            ->where('is_unfollowed', 0)
            ->where('is_deleted', 0)
            ->count();
        return $user_follow_count;
    }


    private function get_user_profile($id)
    {
        $user_profile = DB::table('user')
            ->join('tweet', 'user.id', '=', 'tweet.user_id')
            ->select('user.id', 'name', 'disp_name', 'profile', 'icon_url',
                'content', 'tweet.created_at as tweet_created_at')
            ->where('user.id', $id)
            ->where('user.is_deleted', 0)
            ->where('tweet.is_deleted', 0)
            ->orderBy('tweet.created_at', 'desc')
            ->first();
        return $user_profile;
    }

    private function get_follow_count($sess_user_id, $id)
    {
        $follow_count = DB::table('follow')
            ->select('user_id', 'following_user_id')
            ->where('user_id', $sess_user_id)
            ->where('following_user_id', $id)
            ->where('is_unfollowed', 0)
            ->where('is_deleted', 0)
            ->count();
        return $follow_count;
    }

    private function get_user_list($arr_user_id)
    {
        $user_list = DB::table('user')
            ->select('id', 'name', 'disp_name', 'profile', 'icon_url')
            ->whereIn('id', $arr_user_id)
            ->where('is_deleted', 0)
            ->get();
        return $user_list;
    }

    private function get_loggedin_user()
    {
        if (session()->has('user_id')) {
            $sess_user_id = session('user_id');
            $loggedin_user = DB::table('user')
                ->where('id', $sess_user_id)
                ->where('is_deleted', 0)
                ->first();
            return $loggedin_user;
        }
        return null;
    }

    private function validate_profile($request)
    {
//        dd($_FILES['icon_url']['size']);
//        dd($request->file('icon_url'));

        if ($_FILES['icon_url']['size'] > 0) {
            $get_image_size = getimagesize($request->file('icon_url'));
            $width = $get_image_size[0];
            $height = $get_image_size[1];

            $rules = [
                'icon_url' => 'max:300|dimensions:width=' . $height
            ];
            $messages = [
                'icon_url.max' => 'アイコンは :maxキロバイト以下でアップして下さい',
                'icon_url.dimensions' => '正方形のアイコンをアップして下さい'
            ];
        } else {
            $rules = [
                'icon_url' => 'max:300'
            ];
            $messages = [
                'icon_url.max' => 'アイコンは :maxキロバイト以下でアップして下さい',
            ];
        }


//        $rules = [
//            # 第3引数は除外対象ID
//            'name' => 'required|max:16|unique:user,name,' . $sess_user_id,
//            'disp_name' => 'max:20',
//            'password' => 'required|max:50',
//        ];
//
//        $messages = [
//            'name.required' => 'ユーザー名を入力してください',
//            'name.max' => 'ユーザー名は :max 文字までで入力して下さい',
//            'name.unique' => 'そのユーザー名は既に使われています。別のユーザー名を入力して下さい',
//            'disp_name.max' => '表示名は :max 文字までで入力して下さい',
//            'password.required' => 'パスワードを入力してください',
//            'password.max' => 'パスワードは :max 文字までで入力して下さい',
//        ];
//
        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return $validator;
        }

//        if ($validator->passes()) {
//        }
        return null;
    }

    private function validate_account($request)
    {
        $sess_user_id = session('user_id');

        $rules = [
            # 第3引数は除外対象ID
            'name' => 'required|max:16|unique:user,name,' . $sess_user_id,
            'disp_name' => 'required|max:20',
            'password' => 'required|max:50',
            'confirm_password' => 'required|max:50|same:password',
        ];

        $messages = [
            'name.required' => 'ユーザー名を入力してください',
            'name.max' => 'ユーザー名は :max 文字までで入力して下さい',
            'name.unique' => 'そのユーザー名は既に使われています。別のユーザー名を入力して下さい',
            'disp_name.required' => '表示名を入力してください',
            'disp_name.max' => '表示名は :max 文字までで入力して下さい',
            'password.required' => 'パスワードを入力してください',
            'password.max' => 'パスワードは :max 文字までで入力して下さい',
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

    private function get_ext($request)
    {
        $mime_type = $request->icon_url['type'];
        $arr = explode('/', $mime_type);
        $ext = end($arr);

        return $ext;
    }

    private function get_tmp_file($request, $sess_user_id)
    {
        $ext = $this->get_ext($request);
        $read_tmp = public_path() . "/img/tmp/" . 'icon_' . $sess_user_id . '.' . $ext;
        $fopen_tmp = fopen($read_tmp, 'r');

        return $fopen_tmp;
    }

    private function put_tmp_file_to_s3($file_name, $fopen_tmp)
    {
        /*public状態にしてup*/
        Storage::disk("s3")->put(
            $file_name,
            $fopen_tmp,
            'public'
        );
    }

    private function back_with_error($validator)
    {
        return redirect()->back()
            ->withErrors($validator)
            ->withInput();
    }

    private function exist_following_user($sess_user_id, $following_user_id)
    {
        $exist_following_user = DB::table('follow')
            ->where('user_id', $sess_user_id)
            ->where('following_user_id', $following_user_id)
            ->where('is_deleted', 0)
            ->first();

        return $exist_following_user;
    }

    public function cache_all_clear()
    {
        #全キャッシュ削除
        Cache::flush();
        return null;
    }
}
