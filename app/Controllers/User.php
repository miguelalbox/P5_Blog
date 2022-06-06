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

        if (!$request->session->hasSession) {
            global $framework;
            $framework->redirect("/login");
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
        $fonction = "user" . ucfirst($this->request->uri[2]);
        if (!method_exists($this, $fonction)) $fonction = "page404";
        $this->$fonction();
    }

    public function userAjouter()
    {
        $error = false;
        $msg = "";
        if ($this->request->method === "POST") {
            global $framework;
            try {
                $auteur = new Users();
                $auteur->ajouteUtilisateur([
                    "first_name" => $this->request->post["first_name"],
                    "last_name" => $this->request->post["last_name"],
                    "email" => $this->request->post["email"],
                    "password" => $this->request->post["password"],
                    "civility" => $this->request->post["civility"],
                ]);
                $msg = "l'utilisateur à bien été enregistré";
                $framework->addNotification("succeed", "l'utilisateur à bien été enregistré");
                $framework->redirect("/admin/users");
            } 
            catch (\Throwable $err) {
                $framework->addNotification("error", "Un problème est apparu lors de l'enregistrement");
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

    public function userEditer()
    {
        $error = false;
        $msg = "";
        global $framework;
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
                $framework->addNotification("succeed", "l'utilisateur à bien été modifié");
                $framework->redirect("/admin/users");
            }
            $user->getUserInfo($this->request->uri[3]);
        } 
        catch (\Throwable $err) {
            $framework->addNotification("error", "Un problème est apparu lors de l'enregistrement");
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

    public function userSuprimer()
    {
        $error = false;
        $msg = "";
        global $framework;
        try {
            $user = new Users();

            $user->removeUser([
                "id" => $this->request->uri[3]
            ]);
            $framework->addNotification("succeed", "L'utilisateur a bien été suprimé");
            $framework->redirect("/admin/users");
        } 
        catch (\Throwable $err) {
            $framework->addNotification("error", "Un problème est apparu lors de l'enregistrement");
        } 
        finally {
            $this->template = "backoffice/users/users";
            $this->data = [
                'menu' => 'utilisateurs',
                "error" => $error,
                "message" => $msg,
            ];
        }
    }
}
