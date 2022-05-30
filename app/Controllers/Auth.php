<?php

namespace App\Ctrl;

use App\Models\Users;
use \Error;

class Auth{
    public function login($email, $password){
        $user = new Users();
        $hashedPwd = $user->login($email);
        if (!password_verify($password, $hashedPwd)) throw new Error("mot de passe non valide");
        global $framework;
        $framework->session->setSession("user", [
            "first_name"=> $user->first_name,
            "last_name"=> $user->last_name,
            "email"=> $user->email,
            "civility"=> $user->civility,
            "role"=> $user->role,
            "id"=> $user->id,
        ]);
        //die(var_dump($framework->session));
        // $framework->session->setSession("user", $user);
        // $framework->session->setSession("hasSession", true);
    }

    public function hash($password){
        return password_hash($password, PASSWORD_DEFAULT);
    }

    public function logout(){
        global $framework;
        $framework->session->delete();
    }
}