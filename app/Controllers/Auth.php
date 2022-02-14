<?php

namespace App\Ctrl;

use App\Models\Users;
use \Error;

class Auth{
    public static function login($email, $password){
        $user = new Users();
        $hashedPwd = $user->login($email);
        if (!password_verify($password, $hashedPwd)) throw new Error("mot de passe non valide");
        global $request;
        $request->session->setSession("user", $user);
        $request->session->setSession("hasSession", true);
    }

    public static function hash($password){
        return password_hash($password, PASSWORD_DEFAULT);
    }
}