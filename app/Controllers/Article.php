<?php

namespace App\Ctrl;

use App\Models\Articles;
use App\Models\Users;
use App\Models\Categories;

class Article
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
        $fonction = "article" . ucfirst($this->request->uri[2]);

        if (!method_exists($this, $fonction)) $fonction = "page404";
        $this->$fonction();
    }

    public function articleAjouter()
    {
        $error = false;
        $msg = "";
        $categorie = new Categories();
        if ($this->request->method === "POST") {
            global $framework;
            try {
                global $framework;
                $image =  new Image($framework->files);
                if (!$image->isValid() || !$image->hasImage) throw (["msg" => "l'image n'est pas valide"]);

                $article = new Articles();

                $article->ajouteArticle([
                    "title" => $this->request->post["title"],
                    "image" => $image->getRelativePath(),
                    "content" => $this->request->post["content"],
                    "category" => (int)($this->request->post["category"]),
                    "idAuteur" => $this->request->post["idAuteur"],
                    "chapo" => $this->request->post["chapo"],
                ]);
                $framework->addNotification("succeed", "l'article à bien été enregistré");
                $framework->redirect("/admin/articles");
            } 
            catch (\Throwable $err) {
                $framework->addNotification("error", "Un problème est apparu lors de l'enregistrement");
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

    public function articleEditer()
    {
        $error = false;
        $msg = "";
        global $framework;
        try {
            //apppeler model
            $article = new Articles();
            $categorie = new Categories();
            $article->getArticleInfo($this->request->uri[3]);
            if ($this->request->method === "POST") {
                global $framework;
                $image =  new Image($framework->files);

                if ($image->hasImage) {
                    
                    if (!$image->isValid()) throw (["msg" => "l'image n'est pas valide"]);
                    $image->removePrevious($this->request->uri[3]);
                }
                //apppeler model
                $article->updateArticle([
                    "title" => $this->request->post["title"],
                    "image" => $image->hasImage ? $image->getRelativePath() : $article->image,
                    "content" => $this->request->post["content"],
                    "category" => (int)($this->request->post["category"]),
                    "id" => $this->request->uri[3],
                    "idAuteur" => $this->request->post["idAuteur"],
                    "chapo" => $this->request->post["chapo"],
                ]);
                $msg = "l'article à bien été modifié";
                $framework->addNotification("succeed", "l'article à bien été modifié");
                $framework->redirect("/admin/articles");
                
            }
        } 
        catch (\Throwable $err) {
            $framework->addNotification("error", "Un problème est apparu lors de l'enregistrement");
        } 
        finally {
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

    public function articleSuprimer()
    {
        $error = false;
        $msg = "";
        global $framework;
        try {
            global $framework;

            $article = new Articles();

            $image =  new Image($framework->files);

            $image->removePrevious($this->request->uri[3]);

            $article->removeArticle([
                "id" => $this->request->uri[3]
            ]);
            
            $framework->addNotification("succeed", "l'article à bien été suprimé");
            $framework->redirect("/admin/articles");
        } 
        catch (\Throwable $err) {
            $framework->addNotification("error", "Un problème est apparu lors de la supression");
        } 
        finally {
            $this->template = "backoffice/articles/articles";
            $this->data = [
                'menu' => 'articles',
                "error" => $error,
                "message" => $msg,
            ];
        }
    }
}
