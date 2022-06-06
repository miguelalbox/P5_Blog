<?php

namespace App\Ctrl;

use App\Models\Users;

class Author
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

    public function auteurs()
    {
        $users = new Users();

        $usersList = $users->getAuthors();

        if (count($this->request->uri) === 2) {
            $this->template = "backoffice/auteurs/auteurs";
            $this->data = [
                'menu' => 'auteurs',
                'auteurs' => $usersList

            ];
            return;
        }
        $fonction = "auteur" . ucfirst($this->request->uri[2]);

        if (!method_exists($this, $fonction)) $fonction = "page404";
        $this->$fonction();
    }


    public function auteurAjouter()
    {
        $error = false;
        $msg = "";
        if ($this->request->method === "POST") {
            global $framework;
            try {
                //apppeler model
                $auteur = new Users();

                $auteur->ajouteAuteur([
                    "first_name" => $this->request->post["first_name"],
                    "last_name" => $this->request->post["last_name"],
                    "email" => $this->request->post["email"],
                    "password" => $this->request->post["password"],
                    "civility" => $this->request->post["civility"],
                ]);
                $msg = "l'auteur à bien été enregistré";
                $framework->addNotification("succeed", "l'utilisateur à bien été ajouté");
                $framework->redirect("/admin/auteurs");
            } 
            catch (\Throwable $err) {
                $framework->addNotification("error", "Un problème est apparu lors de l'enregistrement");
            }
        };
        $this->template = "backoffice/auteurs/ajouter-modifier-auteur";
        $this->data = [
            'menu' => 'auteurs',
            "error" => $error,
            "message" => $msg,
            "action" => "Ajouter",
            "title" => "Nouveau Auteur",
            "first_name" => "",
            "last_name" => "",
            "email" => "",
            "password" => "",
            "civility" => "",
        ];
    }

    public function auteurEditer()
    {
        $error = false;
        $msg = "";
        global $framework;
        try {
            $auteur = new Users();
            if ($this->request->method === "POST") {
                $auteur->modifierAuteur([
                    "first_name" => $this->request->post["first_name"],
                    "last_name" => $this->request->post["last_name"],
                    "email" => $this->request->post["email"],
                    "password" => $this->request->post["password"],
                    "civility" => $this->request->post["civility"],
                    "id" => $this->request->uri[3]
                ]);
                $msg = "l'auteur à bien été modifié";
                $framework->addNotification("succeed", "l'auteur à bien été modifié");
                $framework->redirect("/admin/auteurs");
            }
            $auteur->getUserInfo($this->request->uri[3]);
        } 
        catch (\Throwable $err) {
            $framework->addNotification("error", "Un problème est apparu lors de l'enregistrement");
        }
        $this->template = "backoffice/auteurs/ajouter-modifier-auteur";
        $this->data = [
            'menu' => 'auteurs',
            "error" => $error,
            "message" => $msg,
            "action" => "Modifier",
            "title" => "Modifier Auteur",
            "first_name" => $auteur->first_name,
            "last_name" => $auteur->last_name,
            "email" => $auteur->email,
            "password" => $auteur->password,
            "civility" => $auteur->civility,

        ];
    }
    public function auteurSuprimer()
    {
        $error = false;
        $msg = "";
        global $framework;
        try {
            $user = new Users();

            $user->removeUser([
                "id" => $this->request->uri[3]
            ]);

            $framework->addNotification("succeed", "L'auteur a bien été suprimé");
            $framework->redirect("/admin/auteurs");
        } 
        catch (\Throwable $err) {
            $framework->addNotification("error", "Un problème est apparu lors de l'enregistrement");
        } 
        finally {
            $this->template = "backoffice/auteurs/auteurs";
            $this->data = [
                'menu' => 'auteurs',
                "error" => $error,
                "message" => $msg,
            ];
        }
    }
}
