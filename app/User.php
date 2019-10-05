<?php

namespace App;

use App\Http\Controllers\Profile\ProfileEditController;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Validator;
use DB;

//use Exception;

class User extends Model
{

    protected $table = 'user';

    public static function get_all_user()
    {
        return self::query()
            ->where('is_deleted', 0)
            ->paginate(20);
    }

    public static function get_all_user_name()
    {
        return self::query()
            ->select('id', 'name')
            ->where('is_deleted', 0)
            ->get();
    }

    public static function get_all_user_disp_name()
    {
        return self::query()
            ->select('id', 'disp_name')
            ->where('is_deleted', 0)
            ->get();
    }

    public static function get_user_by_name($name)
    {
        return self::query()
            ->select('id', 'name', 'password', 'disp_name')
            ->where('name', $name)
            ->where('is_deleted', 0)
            ->first();
    }

    public static function get_loggedin_user($sess_user_id)
    {
        return self::query()
            ->where('id', $sess_user_id)
            ->where('is_deleted', 0)
            ->first();
    }

    public static function get_user_profile($id)
    {
        return self::query()
            ->join('tweet', 'user.id', '=', 'tweet.user_id')
            ->select('user.id', 'name', 'disp_name', 'profile', 'icon_url',
                'content', 'tweet.created_at as tweet_created_at')
            ->where('user.id', $id)
            ->where('user.is_deleted', 0)
            ->where('tweet.is_deleted', 0)
            ->orderBy('tweet.created_at', 'desc')
            ->first();
    }

    public static function get_user_list($arr_user_id)
    {
        return self::query()
            ->select('id', 'name', 'disp_name', 'profile', 'icon_url')
            ->whereIn('id', $arr_user_id)
            ->where('is_deleted', 0)
            ->get();
    }

    public function store_signup_with_default($name, $disp_name, $pass, $default_icon_url)
    {
        return DB::transaction(function () use ($name, $disp_name, $pass, $default_icon_url) {
            $user = new User;
            $user->name = $name;
            $user->disp_name = $disp_name;
            $user->password = password_hash($pass, PASSWORD_BCRYPT);
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
            $tweet->content = $user->name . ' (@' . $user->name . ')' . 'がツイートプログラムに参加しました';
            $tweet->save();
//            throw new \Exception("これ例外_1");
        });
    }

    public function store_profile($request, $sess_user_id)
    {
        return DB::transaction(function () use ($request, $sess_user_id) {
            $user = User::find($sess_user_id);
            $user->profile = $request->profile;
            # iconがアップされていたら
            if ($request->icon_url['size'] > 0) {
                $ext = ProfileEditController::get_obj_ext($request);
                $fopen_tmp = ProfileEditController::get_fopen_tmp_file($request, $sess_user_id);

                $aws_url = env('AWS_URL');
                $file_name = 'icon_' . $sess_user_id . '.' . $ext;
                ProfileEditController::put_tmp_file_to_s3($file_name, $fopen_tmp);
                $user->icon_url = $aws_url . '/' . $file_name;

                # tmpファイル削除
                ProfileEditController::delete_tmp_file($sess_user_id);
            }
            $user->save();
        });
    }

    public function store_account($sess_user_id, $name, $disp_name, $pass)
    {
        return DB::transaction(function () use ($sess_user_id, $name, $disp_name, $pass) {
            $user = User::find($sess_user_id);
            $user->name = $name;
            $user->disp_name = $disp_name;
            $user->password = password_hash($pass, PASSWORD_BCRYPT);
//              throw new \Exception('testや！！');
            $user->save();
        });
    }

    public static function validate_login(Request $request)
    {
        $rules = [
            'name' => 'required|max:16|regex:/^[a-zA-Z0-9]+$/',
            'password' => 'required|max: 255',
        ];

        $messages = [
            'name.required' => 'ユーザー名を入力してください',
            'name.max' => 'ユーザー名は :max 文字以内で入力して下さい',
            'name.regex' => 'ユーザー名は半角英数字で入力して下さい',
            'password.required' => 'パスワードを入力してください',
            'password.max' => 'パスワードは :max 文字以内で入力して下さい',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);
        return $validator;
    }

    public static function validate_signup(Request $request)
    {
        $rules = [
            'name' => 'required|max:16|regex:/^[a-zA-Z0-9]+$/|unique:user',
            'disp_name' => 'required|max:20',
            'password' => 'required|max:255',
            'confirm_password' => 'required|max:255|same:password',
        ];

        $messages = [
            'name.required' => 'ユーザー名を入力してください',
            'name.max' => 'ユーザー名は :max 文字以内で入力して下さい',
            'name.regex' => 'ユーザー名は半角英数字で入力して下さい',
            'name.unique' => 'そのユーザー名は既に登録されています。別のユーザー名で登録して下さい',
            'disp_name.required' => '表示名を入力してください',
            'disp_name.max' => '表示名は :max 文字以内で入力して下さい',
            'password.required' => 'パスワードを入力してください',
            'password.max' => 'パスワードは :max 文字以内で入力して下さい',
            'confirm_password.required' => 'パスワード確認を入力してください',
            'confirm_password.max' => 'パスワード確認は :max 文字以内で入力して下さい',
            'confirm_password.same' => 'パスワード確認が一致しません',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);
        return $validator;
    }

    public static function validate_profile($request)
    {
        if ($_FILES['icon_url']['size'] > 0) {
            $get_image_size = getimagesize($request->file('icon_url'));
            $width = $get_image_size[0];
            $height = $get_image_size[1];

            $rules = [
                'icon_url' => 'max:300|image|dimensions:width=' . $height
            ];
            $messages = [
                'icon_url.max' => 'アイコンは :maxキロバイト以下でアップして下さい',
                'icon_url.image' => 'アイコンは画像をアップしてください',
                'icon_url.dimensions' => '正方形のアイコンをアップして下さい'
            ];
        } else {
            $rules = [
            ];
            $messages = [
            ];
        }

        $validator = Validator::make($request->all(), $rules, $messages);
        return $validator;

    }

    public static function validate_account($request, $sess_user_id)
    {
        $rules = [
            # 第3引数は除外対象ID
            'name' => 'required|max:16|regex:/^[a-zA-Z0-9]+$/|unique:user,name,' . $sess_user_id,
            'disp_name' => 'required|max:20',
            'password' => 'required|max:255',
            'confirm_password' => 'required|max:255|same:password',
        ];

        $messages = [
            'name.required' => 'ユーザー名を入力してください',
            'name.max' => 'ユーザー名は :max 文字以内で入力して下さい',
            'name.regex' => 'ユーザー名は半角英数字で入力して下さい',
            'name.unique' => 'そのユーザー名は既に使われています。別のユーザー名を入力して下さい',
            'disp_name.required' => '表示名を入力してください',
            'disp_name.max' => '表示名は :max 文字以内で入力して下さい',
            'password.required' => 'パスワードを入力してください',
            'password.max' => 'パスワードは :max 文字以内で入力して下さい',
            'confirm_password.required' => 'パスワード確認を入力してください',
            'confirm_password.max' => 'パスワード確認は :max 文字以内で入力して下さい',
            'confirm_password.same' => 'パスワード確認が一致しません',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);
        return $validator;
    }
}
