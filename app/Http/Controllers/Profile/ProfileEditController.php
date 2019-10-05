<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use App\Http\Controllers\UserController;
use App\User;
use Illuminate\Http\Request;
# tmpファイル削除用
use Illuminate\Support\Facades\File;
use DB;
//# 自力validate用
use Validator;
// 画像upload用
use Storage;

class ProfileEditController extends Controller
{
    public function edit_profile(Request $request)
    {
        if (!session()->has('user_id')) {
            return redirect('/login');
        }

        $sess_user_id = session('user_id');

        # 編集画面でiconがupされていたら
        # _old_inputは既定
        $tmp_icon_data = "";
        $tmp_icon_url = "";
        if (session()->has('_old_input')) {
            $postdata = session('_old_input');

            if (isset($postdata['icon_url'])) {
                $tmp_icon_data = $postdata['icon_url'];
                $tmp_icon_url = $this->get_tmp_file($postdata['icon_url'], $sess_user_id);
            }
        }
        $user = UserController::get_loggedin_user();
        return view('twitter.profile_edit')->with([
            'user' => $user,
            'tmp_icon_url' => $tmp_icon_url,
            'tmp_icon_data' => $tmp_icon_data
        ]);
    }

    public function confirm_profile(Request $request)
    {
        if (!session()->has('user_id')) {
            return redirect('/login');
        }

        $sess_user_id = session('user_id');

        $cancel = $request->cancel;
        if ($cancel == 'キャンセル') {
            # tmpファイル削除
            $this->delete_tmp_file($sess_user_id);
            return redirect('/profile/' . $sess_user_id . '/timeline');
        }

        $validator = $this->validate_profile($request);
        if ($validator) {
            $back_with_error = $this->back_with_error($validator);
            return $back_with_error;
        }

        # iconがアップされたら
        $tmp_icon_url = $this->get_tmp_icon_url($request, $sess_user_id);

        $user = UserController::get_loggedin_user();

        return view('twitter.profile_confirm')->with([
            'user' => $user,
            'request' => $request,
            'tmp_icon_url' => $tmp_icon_url
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

        $store_profile = new User;
        $store_profile->store_profile($request, $sess_user_id);

        return redirect('/profile/' . $sess_user_id . '/timeline');
    }

    private function validate_profile($request)
    {
        $validator = User::validate_profile($request);
        if ($validator->fails()) {
            return $validator;
        }
        return null;
    }

    public static function get_obj_ext($request)
    {
        $mime_type = $request->icon_url['type'];
        $arr = explode('/', $mime_type);
        $ext = end($arr);
        return $ext;
    }

    private function get_arr_ext($request)
    {
        $mime_type = $request['type'];
        $arr = explode('/', $mime_type);
        $ext = end($arr);
        return $ext;
    }

    public static function get_fopen_tmp_file($request, $sess_user_id)
    {
        $ext = self::get_obj_ext($request);
        $read_tmp = public_path() . "/img/tmp/" . 'icon_' . $sess_user_id . '.' . $ext;
        $fopen_tmp = fopen($read_tmp, 'r');
        return $fopen_tmp;
    }

    private function get_tmp_file($postdata, $sess_user_id)
    {
        $ext = $this->get_arr_ext($postdata);
        $read_tmp = "/img/tmp/" . 'icon_' . $sess_user_id . '.' . $ext;
        return $read_tmp;
    }

    public static function put_tmp_file_to_s3($file_name, $fopen_tmp)
    {
        /*public状態にしてup*/
        Storage::disk("s3")->put(
            $file_name,
            $fopen_tmp,
            'public'
        );
    }

    public static function delete_tmp_file($sess_user_id)
    {
        $files = File::allFiles(public_path() . '/img/tmp');
        foreach ($files as $k => $v) {
            $arr = explode('/', $v);
            $tmp_file_name = end($arr);
            $preg_match = preg_match('/^icon_' . $sess_user_id . '/', $tmp_file_name);
            if ($preg_match) {
                File::delete($v);
            }
        }
    }

    private function get_tmp_icon_url($request, $sess_user_id)
    {
        if ($_FILES['icon_url']['size'] > 0) {
            /*tmp imgをmoveして一時フォルダに保存*/
            $icon_name = 'icon_' . $sess_user_id . '.' . $request->file('icon_url')->guessExtension(); // tmpファイル名
            $request->file('icon_url')->move(public_path() . "/img/tmp/", $icon_name);
            $tmp_icon_url = "/img/tmp/" . $icon_name;
        }
        elseif ($request->icon_url['size'] > 0) {
            $ext = self::get_obj_ext($request);
            $icon_name = 'icon_' . $sess_user_id . '.' . $ext; // tmpファイル名
            $tmp_icon_url = "/img/tmp/" . $icon_name;
        }
        else {
            $tmp_icon_url = "";
        }
        return $tmp_icon_url;
    }

    private function back_with_error($validator)
    {
        return redirect()->back()
            ->withErrors($validator)
            ->withInput();
    }
}
