<?php

namespace App\Ctrl;

use App\Models\Users;

class User
{

    private function auteurs()
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


    private function auteur_ajouter()
    {
        $error = false;
        $msg = "";
        if ($this->request->method === "POST") {
            //die(var_dump($this->request));
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
                Tools::redirect("/admin/auteurs");
                //Tools::addNotification("succeed", "l'utilisateur à bien été modifié");
            } catch (\Throwable $err) {
                $error = true;
                $msg = "un problème est apparu lors de l'enregistrement";
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

    private function auteur_editer()
    {
        $error = false;
        $msg = "";
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
                Tools::redirect("/admin/auteurs");
                //Tools::addNotification("succeed", "l'utilisateur à bien été modifié");
            }
            $auteur->getUserInfo($this->request->uri[3]);
        } catch (\Throwable $err) {
            $error = true;
            $msg = "un problème est apparu lors de l'enregistrement";
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
    private function auteur_suprimer()
    {
        $error = false;
        $msg = "";
        try {
            //apppeler model

            $user = new Users();

                $user->removeUser([
                    "id" => $this->request->uri[3]
                ]);
                //die(var_dump($article));
                
                $msg = "l'auteur à bien été suprimé";
                Tools::redirect("/admin/auteurs");
            
            
                
        } catch (\Throwable $err) {
            die(var_dump($err));
            $error = true;
            $msg = "un problème est apparu lors de l'enregistrement";
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
    
    private function users()
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

    private function user_ajouter()
    {
        $error = false;
        $msg = "";
        if ($this->request->method === "POST") {
            //die(var_dump($this->request));
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
                Tools::redirect("/admin/users");
                //Tools::addNotification("succeed", "l'utilisateur à bien été enregistré");
                
            } catch (\Throwable $err) {
                $error = true;
                $msg = "un problème est apparu lors de l'enregistrement";
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

    private function user_editer()
    {
        $error = false;
        $msg = "";
        try {
            //apppeler model
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
                Tools::redirect("/admin/users");
                //Tools::addNotification("succeed", "l'utilisateur à bien été modifié");
            }
            $user->getUserInfo($this->request->uri[3]);
            
        } catch (\Throwable $err) {
            $error = true;
            $msg = "un problème est apparu lors de l'enregistrement";
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

    private function user_suprimer()
    {
        $error = false;
        $msg = "";
        try {
            //apppeler model

            $user = new Users();

                $user->removeUser([
                    "id" => $this->request->uri[3]
                ]);
                //die(var_dump($article));
                
                $msg = "l'utilisateur à bien été suprimé";
                Tools::redirect("/admin/users");
            
            
                
        } catch (\Throwable $err) {
            die(var_dump($err));
            $error = true;
            $msg = "un problème est apparu lors de l'enregistrement";
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