<?php

namespace App\Ctrl;

use App\Models\Articles;
use App\Models\Categorie;

class Article{
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
            try {
                $image =  new Image($_FILES);
                // die(var_dump($image->isValid()).var_dump($image->getRelativePath()));
                if (!$image->isValid()) throw (["msg" => "l'image n'est pas valide"]);
                //apppeler model
                $article = new Articles();

                $article->ajouteArticle([
                    "title" => $this->request->post["title"],
                    "image" => $image->getRelativePath(),
                    "content" => $this->request->post["content"],
                    "category" => intval($this->request->post["category"]),
                    "idAuteur" => $this->request->session["data"]["id"]
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
                    "image" => $image->getRelativePath(),
                    "content" => $this->request->post["content"],
                    "category" => intval($this->request->post["category"]),
                    "id" => $this->request->uri[3]
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
                Tools::redirect("/admin/categories");
                //Tools::addNotification("succeed", "l'utilisateur à bien été enregistré");
                
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
}