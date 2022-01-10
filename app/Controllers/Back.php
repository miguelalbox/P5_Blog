<?php

namespace App\Ctrl;

use App\Models\Articles;
use App\Models\Categorie;
use App\Models\Users;

class Back
{
    private $request;
    public $template;
    public $data = [];
    public $current;
    public function __construct($request)
    {
        $this->request = $request;
        $fonction = $request->uri[1];
        if ($fonction === "" || !isset($fonction)) $fonction = "backoffice";
        if (!method_exists($this, $fonction)) $fonction = "page404";
        $this->$fonction();
    }


    private function backoffice()
    {
        $this->template = "backoffice/backoffice";
        $this->data = [
            'menu' => 'backoffice'
        ];
    }
    private function auteurs()
    {

        // /auteurs => onn affiche la liste des auteurs
        // /auteurs/edite => on edite
        // /auteurrs/ajoute => on ajoute
        if (count($this->request->uri) === 2) {

            $this->template = "backoffice/auteurs/auteurs";
            $this->data = [
                'menu' => 'auteurs',

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

    private function users()
    {

        if (count($this->request->uri) === 2) {
            $this->template = "backoffice/users/users";
            $this->data = [
                'menu' => 'utilisateurs',
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




    private function articles()
    {


        if (count($this->request->uri) === 2) {
            $this->template = "backoffice/articles/articles";
            $this->data = [
                'menu' => 'articles',
            ];
            return;
        }
        //die(var_dump($this->request->uri));
        $fonction = "article_" . $this->request->uri[2];
        // die(var_dump($fonction));
        if (!method_exists($this, $fonction)) $fonction = "page404";
        $this->$fonction();
    }

    private function article_ajouter()
    {
        $error = false;
        $msg = "";
        $categorie = new Categorie();
        if ($this->request->method === "POST") {
            // die(var_dump($this->request));
            try {
                $image =  new Image($_FILES);
                // die(var_dump($image->isValid()).var_dump($image->getRelativePath()));
                if (!$image->isValid()) throw (["msg" => "l'image n'est pas valide"]);
                //apppeler model
                $article = new Articles();

                $article->ajouteArticle([
                    "title" => $this->request->post["title"],
                    "image" => $image->getPath(),
                    "content" => $this->request->post["content"],
                    "category" => intval($this->request->post["category"]),

                ]);
                $msg = "l'article à bien été enregistré";
            } catch (\Throwable $err) {
                die(var_dump($err));
                $error = true;
                //$msg= $err["msg"] ? $err["msg"] : "un problème est apparu lors de l'enregistrement";
            }
        };
        $this->template = "backoffice/articles/ajouter-modifier-articles";
        $this->data = [
            'menu' => 'articles',
            "error" => $error,
            "message" => $msg,
            "selectCategories" => $categorie->getCategories(),
            "title" => "Ajouter Article",
            "titre_article" => "",
            "content" => "",
        ];
    }

    private function article_editer()
    {
        $error = false;
        $msg = "";
        try {
            //apppeler model

            $article = new Articles();
            $categorie = new Categorie();
            if ($this->request->method === "POST") {
                $image =  new Image($_FILES);

                // die(var_dump($image->isValid()).var_dump($image->getRelativePath()));
                if (!$image->isValid()) throw (["msg" => "l'image n'est pas valide"]);
                $image->removePrevious($this->request->uri[3]);
                //apppeler model

                $article->updateArticle([
                    "title" => $this->request->post["title"],
                    "image" => $image->getPath(),
                    "content" => $this->request->post["content"],
                    "category" => intval($this->request->post["category"]),
                    "id" => $this->request->uri[3]
                ]);
                $msg = "l'article à bien été modifié";
            }
            $article->getArticleInfo($this->request->uri[3]);
                
        } catch (\Throwable $err) {
            die(var_dump($err));
            $error = true;
            $msg = "un problème est apparu lors de l'enregistrement";
        } finally {
            // die(var_dump($article));
            $this->template = "backoffice/articles/ajouter-modifier-articles";
            $this->data = [
                'menu' => 'articles',
                "error" => $error,
                "message" => $msg,
                "selectCategories" => $categorie->getCategories(),
                "title" => "Modifier Article",
                "titre_article" => $article->title,
                "content" => $article->content,
            ];
        }
    }




    private function page404()
    {
        $this->template = "backoffice/404";
    }
}
