<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    // sql実行時table名がusersになるので
    protected $table = 'user';

    // public static $rules = array(
    //     'name' => 'min:6',
    // );

//    return $this->hasMany('App\Follow');
   
}
