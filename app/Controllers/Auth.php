<?php

namespace App\Ctrl;

use App\Models\Users;

class Auth{
    public static function login($email, $password){
        $user = new Users();
        $user->login($email);
        if (!password_verify($password, $user->password)) throw ""; //TODO faire la gestion d'erreur mot de passe pas vlaide
        unset($user->password);
        global $request;
        $request->session->setSession("user", $user);
    }

    public static function hash($password){
        return password_hash('rasmuslerdorf', PASSWORD_BCRYPT, [
            'cost' => 11
        ]);
    }
}