<?php

namespace App\Ctrl;

use App\Models\Articles;
use App\Models\Users;
use App\Models\Categories;

class Article{
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
    public function articles()
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

    public function article_ajouter()
    {
        $error = false;
        $msg = "";
        $categorie = new Categories();
        if ($this->request->method === "POST") {
            // die(var_dump($this->request->post));
            global $tools;
            try {
                $image =  new Image($_FILES);
                if (!$image->isValid() || !$image->hasImage) throw (["msg" => "l'image n'est pas valide"]);
                //apppeler model
                $article = new Articles();

                $article->ajouteArticle([
                    "title" => $this->request->post["title"],
                    "image" => $image->getRelativePath(),
                    "content" => $this->request->post["content"],
                    "category" => (int)($this->request->post["category"]),
                    "idAuteur" => $this->request->post["idAuteur"],
                    "chapo" => $this->request->post["chapo"],
                ]);
                //$msg = "l'article à bien été enregistré";
                $tools->addNotification("succeed", "l'article à bien été enregistré");
                $tools->redirect("/admin/articles");
            } catch (\Throwable $err) {
                $tools->addNotification("error", "Un problème est apparu lors de l'enregistrement");
                //die(var_dump($err));
                //$error = true;
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
            "chapo" => "",
            "auteurs" => $auteurs->getAuthors(),
            "idAuteur" => $this->request->session->data["user"]["id"]
        ];
    }

    public function article_editer()
    {
        $error = false;
        $msg = "";
        global $tools;
        try {
            //apppeler model
            $article = new Articles();
            $categorie = new Categories();
            $article->getArticleInfo($this->request->uri[3]);
            if ($this->request->method === "POST") {
                // die(var_dump($_FILES));
                $image =  new Image($_FILES);
                // die(var_dump($image->hasImage));
                if ($image->hasImage) {
                    // die(var_dump($image->isValid()).var_dump($image->getRelativePath()));
                    if (!$image->isValid()) throw (["msg" => "l'image n'est pas valide"]);
                    $image->removePrevious($this->request->uri[3]);
                }
                //apppeler model
                $article->updateArticle([
                    "title" => $this->request->post["title"],
                    "image" => $image->hasImage ? $image->getRelativePath() : $article->image,
                    "content" => $this->request->post["content"],
                    "category" => intval($this->request->post["category"]),
                    "id" => $this->request->uri[3],
                    "idAuteur" => $this->request->post["idAuteur"],
                    "chapo" => $this->request->post["chapo"],
                ]);
                $msg = "l'article à bien été modifié";
                $tools->addNotification("succeed", "l'article à bien été modifié");
                $tools->redirect("/admin/articles");
                //$tools->addNotification("succeed", "l'utilisateur à bien été enregistré");
            }
        } catch (\Throwable $err) {
            //die(var_dump($err));
            //$error = true;
            //$msg = "un problème est apparu lors de l'enregistrement";
            $tools->addNotification("error", "Un problème est apparu lors de l'enregistrement");
        } finally {
            // die(var_dump($article));
            $auteurs = new Users();
            $this->template = "backoffice/articles/ajouter-modifier-articles";
            $this->data = [
                'menu' => 'articles',
                "error" => $error,
                "message" => $msg,
                "image" => $article->image,
                "categorie" => $article->category,
                "auteurPost" => $article->id_user,
                "selectCategories" => $categorie->getCategories(),
                "title" => "Modifier Article",
                "titre_article" => $article->title,
                "content" => $article->content,
                "chapo" => $article->chapo,
                "auteurs" => $auteurs->getAuthors(),
                "idAuteur" => $this->request->session->data["user"]["id"]
            ];
        }
    }

    public function article_suprimer()
    {
        $error = false;
        $msg = "";
        global $tools;
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

            //$msg = "l'article à bien été suprimé";
            $tools->addNotification("succeed", "l'article à bien été suprimé");
            $tools->redirect("/admin/articles");
        } catch (\Throwable $err) {
            //die(var_dump($err));
            //$error = true;
            //$msg = "un problème est apparu lors de l'enregistrement";
            $tools->addNotification("error", "Un problème est apparu lors de la supression");
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
}