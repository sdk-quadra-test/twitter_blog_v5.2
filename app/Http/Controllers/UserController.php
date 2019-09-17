<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use DB;

class UserController extends Controller
{
    public function get_all_user(){

        $user = DB::table('user')
            ->where('is_deleted', 0)
            ->paginate(20);

        return view('twitter.user',[
            'user' => $user
        ]);



    }
}
