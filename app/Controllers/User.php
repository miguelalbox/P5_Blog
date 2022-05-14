<?php

namespace App\Ctrl;

use App\Models\Users;

class User
{
    private $request;
    public $template;
    public $data = [];
    public $current;
    public function __construct($request)
    {

        // $auth = Auth::login("mikyfiestas@gmail.com", "miguel123");
        // die(var_dump($request->session));

        if (!$request->session->hasSession) {
            global $tools;
            $tools->redirect("/login"); //throw new Error("pas d'utilisateur connecté");
        }

        $this->request = $request;
        $fonction = $request->uri[1];
        if ($fonction === "" || !isset($fonction)) $fonction = "backoffice";
        if (!method_exists($this, $fonction)) $fonction = "page404";
        $this->$fonction();
    }

    public function users()
    {
        $users = new Users();

        $usersList = $users->getUsers();


        if (count($this->request->uri) === 2) {
            $this->template = "backoffice/users/users";
            $this->data = [
                'menu' => 'utilisateurs',
                'utilisateurs' => $usersList
            ];
            return;
        }
        //die(var_dump($this->request->uri));
        $fonction = "user_" . $this->request->uri[2];
        // die(var_dump($fonction));
        if (!method_exists($this, $fonction)) $fonction = "page404";
        $this->$fonction();
    }

    public function user_ajouter()
    {
        $error = false;
        $msg = "";
        if ($this->request->method === "POST") {
            //die(var_dump($this->request));
            global $tools;
            try {
                //apppeler model
                $auteur = new Users();
                $auteur->ajouteUtilisateur([
                    "first_name" => $this->request->post["first_name"],
                    "last_name" => $this->request->post["last_name"],
                    "email" => $this->request->post["email"],
                    "password" => $this->request->post["password"],
                    "civility" => $this->request->post["civility"],
                ]);
                $msg = "l'utilisateur à bien été enregistré";
                $tools->addNotification("succeed", "l'utilisateur à bien été enregistré");
                $tools->redirect("/admin/users");
            } catch (\Throwable $err) {
                //$error = true;
                //$msg = "un problème est apparu lors de l'enregistrement";
                $tools->addNotification("error", "Un problème est apparu lors de l'enregistrement");
            }
        };
        $this->template = "backoffice/users/ajouter-modifier-user";
        $this->data = [
            'menu' => 'utilisateurs',
            "error" => $error,
            "message" => $msg,
            "action" => "Ajouter",
            "title" => "Nouveau Utilisateur",
            "first_name" => "",
            "last_name" => "",
            "email" => "",
            "password" => "",
            "civility" => "",
        ];
    }

    public function user_editer()
    {
        $error = false;
        $msg = "";
        global $tools;
        try {
            $user = new Users();
            if ($this->request->method === "POST") {
                $user->modifierUtilisateur([
                    "first_name" => $this->request->post["first_name"],
                    "last_name" => $this->request->post["last_name"],
                    "email" => $this->request->post["email"],
                    "password" => $this->request->post["password"],
                    "civility" => $this->request->post["civility"],
                    "id" => $this->request->uri[3]
                ]);
                $msg = "l'utilisateur à bien été modifié";
                $tools->addNotification("succeed", "l'utilisateur à bien été modifié");
                $tools->redirect("/admin/users");
            }
            $user->getUserInfo($this->request->uri[3]);
        } catch (\Throwable $err) {
            //$error = true;
            //$msg = "un problème est apparu lors de l'enregistrement";
            $tools->addNotification("error", "Un problème est apparu lors de l'enregistrement");
        }
        $this->template = "backoffice/users/ajouter-modifier-user";
        $this->data = [
            'menu' => 'utilisateurs',
            "error" => $error,
            "message" => $msg,
            "action" => "Modifier",
            "title" => "Modifier Utilisateur",
            "first_name" => $user->first_name,
            "last_name" => $user->last_name,
            "email" => $user->email,
            "password" => $user->password,
            "civility" => $user->civility,

        ];
    }

    public function user_suprimer()
    {
        $error = false;
        $msg = "";
        global $tools;
        try {
            //apppeler model
            $user = new Users();

            $user->removeUser([
                "id" => $this->request->uri[3]
            ]);
            //die(var_dump($article));

            //$msg = "l'utilisateur à bien été suprimé";
            $tools->addNotification("succeed", "L'utilisateur a bien été suprimé");
            $tools->redirect("/admin/users");
        } catch (\Throwable $err) {
            //die(var_dump($err));
            //$error = true;
            //$msg = "un problème est apparu lors de l'enregistrement";
            $tools->addNotification("error", "Un problème est apparu lors de l'enregistrement");
        } finally {
            // die(var_dump($article));
            $this->template = "backoffice/users/users";
            $this->data = [
                'menu' => 'utilisateurs',
                "error" => $error,
                "message" => $msg,
            ];
        }
    }
}