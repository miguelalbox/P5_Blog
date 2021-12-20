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
        if ($fonction === "" || ! isset($fonction)) $fonction = "backoffice";
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
        
        $this->template = "backoffice/auteurs/auteurs";
        $this->data = [
            'menu' => 'auteurs',
        ];
    }
    private function auteur()
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
                $msg="un problème est apparu lors de l'enregistrement";
            }
        };
        $this->template = "backoffice/auteurs/ajouter-auteur";
        $this->data = [
            'menu' => 'auteurs',
            "error" => $error,
            "message" =>$msg
        ];
    }
    private function users()
    {
        
        $this->template = "backoffice/users/users";
        $this->data = [
            'menu' => 'utilisateurs',
        ];
    }
    private function user()
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
                $msg="un problème est apparu lors de l'enregistrement";
            }
        };
        $this->template = "backoffice/users/ajouter-user";
        $this->data = [
            'menu' => 'utilisateurs',
            "error" => $error,
            "message" =>$msg
        ];
    }

    private function articles()
    {
        
        $this->template = "backoffice/articles/articles";
        $this->data = [
            'menu' => 'articles',
        ];
    }

    private function article()
    {
        $error = false;
        $msg = "";
        $categorie = new Categorie();
        if ($this->request->method === "POST") {
            // die(var_dump($this->request));
            try {
                $image =  new Image($_FILES);
               // die(var_dump($image->isValid()).var_dump($image->getRelativePath()));
                if ( ! $image->isValid()) throw (["msg"=>"l'image n'est pas valide"]);
                //apppeler model
                $article = new Articles();
                $article->ajouteArticle([
                    "title" => $this->request->post["title"],
                    "image" => $image->getPath(),
                    "content" => $this->request->post["content"],
                    "category" => $this->request->post["idCategorie"],
                    
                ]);
                $msg = "l'article à bien été enregistré";
            } catch (\Throwable $err) {
                $error = true;
                //$msg= $err["msg"] ? $err["msg"] : "un problème est apparu lors de l'enregistrement";
            }
        };
        $this->template = "backoffice/articles/ajouter-articles";
        $this->data = [
            'menu' => 'articles',
            "error" => $error,
            "message" =>$msg,
            "selectCategories" => $categorie->getCategories()
        ];
    }



    private function page404()
    {
        $this->template = "backoffice/404";
    }
}
