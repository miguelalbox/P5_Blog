<?php

namespace App\Ctrl;

use App\Models\Users;

class Auth{

    public static function login($email, $password){
        $user = new Users();
        $succeed = $user->login($email,$password);
        if ( !$succeed) return false;
        global $request;
        $request->session->setSession("user", $user);
        return true;
    }
}