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
        $request->session->setSession("user", [
            "first_name"=> $user->first_name,
            "last_name"=> $user->last_name,
            "email"=> $user->email,
            "civility"=> $user->civility,
            "role"=> $user->role,
            "id"=> $user->id,
        ]);
        // $request->session->setSession("user", $user);
        // $request->session->setSession("hasSession", true);
    }

    public static function hash($password){
        return password_hash($password, PASSWORD_DEFAULT);
    }

    public static function logout(){
        global $request;
        $request->session->delete();
    }
}