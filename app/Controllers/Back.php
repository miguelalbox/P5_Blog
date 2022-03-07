<?php

namespace App\Ctrl;

use App\Models\Articles;
use App\Models\Categorie;
use App\Models\Users;
use App\Ctrl\Auth;
use App\Ctrl\Tools;
use Error;

class Back
{
    private $request;
    public $template;
    public $data = [];
    public $current;
    public function __construct($request)
    {

        // $auth = Auth::login("mikyfiestas@gmail.com", "miguel123");
        // die(var_dump($request->session));


       if(!$request->session->hasSession) Tools::redirect("/login"); //throw new Error("pas d'utilisateur connecté");

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




    private function articles()
    {

        $articles = new Articles();

        $articleList = $articles->getArticles();

        if (count($this->request->uri) === 2) {
            $this->template = "backoffice/articles/articles";
            $this->data = [
                'menu' => 'articles',
                'articles' => $articleList
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
            //die(var_dump($this->request->session));
            try {
                $image =  new Image($_FILES);
                if (!$image->isValid()) throw (["msg" => "l'image n'est pas valide"]);
                //apppeler model
                $article = new Articles();

                $article->ajouteArticle([
                    "title" => $this->request->post["title"],
                    "image" => $image->getRelativePath(),
                    "content" => $this->request->post["content"],
                    "category" => intval($this->request->post["category"]),
                    "idAuteur" => $this->request->post["idAuteur"]
                ]);
                $msg = "l'article à bien été enregistré";
                Tools::redirect("/admin/articles");
                //Tools::addNotification("succeed", "l'utilisateur à bien été enregistré");
            } catch (\Throwable $err) {
                die(var_dump($err));
                $error = true;
                //$msg= $err["msg"] ? $err["msg"] : "un problème est apparu lors de l'enregistrement";
            }
        };
        $auteurs = new Users();

        $this->template = "backoffice/articles/ajouter-modifier-articles";
        $this->data = [
            'menu' => 'articles',
            "error" => $error,
            "message" => $msg,
            "selectCategories" => $categorie->getCategories(),
            "title" => "Ajouter Article",
            "titre_article" => "",
            "content" => "",
            "auteurs" =>$auteurs->getAuthors(),
            "idAuteur" => $this->request->session->data["user"]["id"]
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
                    "image" => $image->getRelativePath(),
                    "content" => $this->request->post["content"],
                    "category" => intval($this->request->post["category"]),
                    "id" => $this->request->uri[3],
                    "idAuteur" => $this->request->post["idAuteur"]
                ]);
                $msg = "l'article à bien été modifié";
                Tools::redirect("/admin/articles");
                //Tools::addNotification("succeed", "l'utilisateur à bien été enregistré");
            }
            $article->getArticleInfo($this->request->uri[3]);
                
        } catch (\Throwable $err) {
            die(var_dump($err));
            $error = true;
            $msg = "un problème est apparu lors de l'enregistrement";
        } finally {
            // die(var_dump($article));
            $auteurs = new Users();
            $this->template = "backoffice/articles/ajouter-modifier-articles";
            $this->data = [
                'menu' => 'articles',
                "error" => $error,
                "message" => $msg,
                "selectCategories" => $categorie->getCategories(),
                "title" => "Modifier Article",
                "titre_article" => $article->title,
                "content" => $article->content,
                "auteurs" =>$auteurs->getAuthors(),
                "idAuteur" => $this->request->session->data["user"]["id"]
            ];
        }
    }

    private function article_suprimer()
    {
        $error = false;
        $msg = "";
        try {
            //apppeler model

            $article = new Articles();
            
                $image =  new Image($_FILES);

                // die(var_dump($image->isValid()).var_dump($image->getRelativePath()));
                
                $image->removePrevious($this->request->uri[3]);
                //apppeler model

                $article->removeArticle([
                    "id" => $this->request->uri[3]
                ]);
                //die(var_dump($article));
                
                $msg = "l'article à bien été suprimé";
                Tools::redirect("/admin/articles");
            
                
        } catch (\Throwable $err) {
            die(var_dump($err));
            $error = true;
            $msg = "un problème est apparu lors de l'enregistrement";
        } finally {
            // die(var_dump($article));
            $this->template = "backoffice/articles/articles";
            $this->data = [
                'menu' => 'articles',
                "error" => $error,
                "message" => $msg,
            ];
        }
        
    }


    private function categories()
    {
        $categories = new Categorie();

        $categoriesList = $categories->getCategories();

        //die(var_dump($categories));
        if (count($this->request->uri) === 2) {
            $this->template = "backoffice/categories/categories";
            $this->data = [
                'menu' => 'categories',
                'categories' => $categoriesList
            ];
            return;
        }
        //die(var_dump($this->request->uri));
        $fonction = "categorie_" . $this->request->uri[2];
        // die(var_dump($fonction));
        if (!method_exists($this, $fonction)) $fonction = "page404";
        $this->$fonction();
    }

    private function categorie_ajouter()
    {
        $error = false;
        $msg = "";
        if ($this->request->method === "POST") {
            //die(var_dump($this->request));
            try {
                //apppeler model
                $category = new Categorie();

                $category->addCategory([
                    "name" => $this->request->post["name"],
                ]);
                $msg = "la categorie à bien été enregistré";
                Tools::addNotification("succeed", "la categorie à bien été enregistrée");
                Tools::redirect("/admin/categories");
                
                
            } catch (\Throwable $err) {
                $error = true;
                $msg = "un problème est apparu lors de l'enregistrement";
            }
        };
        $this->template = "backoffice/categories/ajouter-modifier-categorie";
        $this->data = [
            'menu' => 'categories',
            "error" => $error,
            "message" => $msg,
            "action" => "Ajouter",
            "title" => "Nouvelle Categorie",
            "first_name" => "",
            "last_name" => "",
            "email" => "",
            "password" => "",
            "civility" => "",
        ];
    }

    private function categorie_editer()
    {
        $error = false;
        $msg = "";
        try {
            //apppeler model
            $categorie = new Categorie();
            if ($this->request->method === "POST") {
                $categorie->updateCategory([
                    "name" => $this->request->post["name"],
                    "id" => $this->request->uri[3]
                ]);
                //$msg = "la categorie à bien été modifié";
                Tools::addNotification("succeed", "l'utilisateur à bien été enregistré");
                Tools::redirect("/admin/categories");
            }
            $categorie->getCategorieInfo($this->request->uri[3]);
        } catch (\Throwable $err) {
            $error = true;
            $msg = "un problème est apparu lors de l'enregistrement";
        }
        $this->template = "backoffice/categories/ajouter-modifier-categorie";
        $this->data = [
            'menu' => 'categories',
            "error" => $error,
            "message" => $msg,
            "action" => "Modifier",
            "title" => "Modifier Categorie",
            "name" => $categorie->name,

        ];
    }

    private function categorie_suprimer()
    {
        $error = false;
        $msg = "";
        try {
            //apppeler model

            $categorie = new Categorie();

                $categorie->removeCategory([
                    "id" => $this->request->uri[3]
                ]);
                //die(var_dump($article));
                
                $msg = "la categorie à bien été suprimé";
                Tools::redirect("/admin/categories");
            
                
        } catch (\Throwable $err) {
            die(var_dump($err));
            $error = true;
            $msg = "un problème est apparu lors de l'enregistrement";
        } finally {
            // die(var_dump($article));
            $this->template = "backoffice/categories/categories";
            $this->data = [
                'menu' => 'categories',
                "error" => $error,
                "message" => $msg,
            ];
        }
        
    }
    

    private function deconnexion(){
        $this->request->session->delete();
        Tools::addNotification("succeed", "vous êtes bien déconnecté");
        Tools::redirect("/login");
    }



    private function page404()
    {
        $this->template = "backoffice/404";
    }
}
