<?php

namespace App\Http\Controllers\Profile;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;

use DB;
//# 自力validate用
use Validator;


class AccountEditController extends Controller
{
    public function edit_account()
    {
        if (!session()->has('user_id')) {
            return redirect('/login');
        }

        $user = UserController::get_loggedin_user();
        return view('twitter.account_edit', [
            'user' => $user
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

        $user = UserController::get_loggedin_user();

        return view('twitter.account_confirm')->with([
            'user' => $user,
            'request' => $request
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

//        $user = User::find($sess_user_id);
        $name = $input['name'];
        $disp_name = $input['disp_name'];
        $pass = $input['password'];

        $store_account = new User;
        $store_account->store_account($sess_user_id, $name, $disp_name, $pass);

        $request->session()->forget('disp_name');
        $request->session()->put('disp_name', $disp_name);

        return redirect('/profile/' . $sess_user_id . '/timeline');
    }

    ####################################
    # private
    ####################################

    private function validate_account($request)
    {
        $sess_user_id = session('user_id');
        $validator = User::validate_account($request, $sess_user_id);

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

}
