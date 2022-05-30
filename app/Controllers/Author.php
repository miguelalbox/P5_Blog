<?php

namespace App\Ctrl;

use App\Models\Users;

class Author{
    private $request;
    public $template;
    public $data = [];
    public $current;
    public function __construct($request)
    {

        // $auth = Auth::login("mikyfiestas@gmail.com", "miguel123");
        // die(var_dump($request->session));

        if (!$request->session->hasSession) {
            global $framework;
            $framework->redirect("/login"); //throw new Error("pas d'utilisateur connecté");
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

        // /auteurs => onn affiche la liste des auteurs
        // /auteurs/edite => on edite
        // /auteurrs/ajoute => on ajoute
        if (count($this->request->uri) === 2) {

            $this->template = "backoffice/auteurs/auteurs";
            $this->data = [
                'menu' => 'auteurs',
                'auteurs' => $usersList

            ];
            return;
        }
        //die(var_dump($this->request->uri));
        $fonction = "auteur_" . $this->request->uri[2];
        // die(var_dump($fonction));
        if (!method_exists($this, $fonction)) $fonction = "page404";
        $this->$fonction();
    }


    public function auteur_ajouter()
    {
        $error = false;
        $msg = "";
        if ($this->request->method === "POST") {
            //die(var_dump($this->request));
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
            } catch (\Throwable $err) {
                //$error = true;
                //$msg = "un problème est apparu lors de l'enregistrement";
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

    public function auteur_editer()
    {
        $error = false;
        $msg = "";
        global $framework;
        try {
            //apppeler model
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
        } catch (\Throwable $err) {
            //$error = true;
            //$msg = "un problème est apparu lors de l'enregistrement";
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
    public function auteur_suprimer()
    {
        $error = false;
        $msg = "";
        global $framework;
        try {
            //apppeler model
            $user = new Users();

            $user->removeUser([
                "id" => $this->request->uri[3]
            ]);
            //die(var_dump($article));

            //$msg = "l'auteur à bien été suprimé";
            $framework->addNotification("succeed", "L'auteur a bien été suprimé");
            $framework->redirect("/admin/auteurs");
        } catch (\Throwable $err) {
            //die(var_dump($err));
            //$error = true;
            //$msg = "un problème est apparu lors de l'enregistrement";
            $framework->addNotification("error", "Un problème est apparu lors de l'enregistrement");
        } finally {
            // die(var_dump($article));
            $this->template = "backoffice/auteurs/auteurs";
            $this->data = [
                'menu' => 'auteurs',
                "error" => $error,
                "message" => $msg,
            ];
        }
    }

}