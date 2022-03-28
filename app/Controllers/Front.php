<?php

namespace App\Ctrl;

// use App\Models\Users;
use App\Ctrl\Auth;
use App\Models\Articles;
use App\Ctrl\Tools;
use App\Models\Categorie;
use App\Models\Commentaire;

class Front
{
    private $request;
    public $template;
    public $data = [];
    public $current;
    public function __construct($request)
    {
           $this->request                               = $request;
           $fonction                                    = $request->uri[0];
        if ($fonction === "") $fonction                 = "home";
        if (!method_exists($this, $fonction)) $fonction = "page404";
        $this->$fonction();
    }

    private function home()
    {
        $posts = new Articles();
        $posts->getTenLastPosts();
        $categories = new Categorie();
        $categories->getCategoriesHome();
        

        $this->template = "index";
        $this->data     = [
            "articles"   => $posts->listPosts,
            "categories" => $categories->listCategorie,
        ];
    }

    private function articles()
    {
        $posts = new Articles();
        $posts->getTenLastPosts();
        $categories = new Categorie();
        $categories->getCategoriesHome();

        $this->template = "articles";
        $this->data     = [
            "articles"   => $posts->listPosts,
            "categories" => $categories->listCategorie,
        ];
    }
    private function article()
    {
        $post = new Articles();
        $post->getArticle( $this->request->uri[1] );
        $comments = new Commentaire();

        // die(var_dump($comments->getValidsComments($this->request->uri[1])));
        if ($this->request->method === "POST") {
            //die(var_dump($this->request));
            try {
                //apppeler model
                $commentaire = new Commentaire();

                $commentaire->addCommentaire([
                    "name" => $this->request->post["name"],
                    "content" => $this->request->post["content"],
                    "articleId"=>$this->request->uri[1]
                ]);
                //$msg = "la categorie à bien été enregistré";
                Tools::addNotification("succeed", "le commentaire à bien été enregistrée");
                Tools::redirect("/article/".$this->request->uri[1]);
                
                
            } catch (\Throwable $err) {
                //$error = true;
                //$msg = "un problème est apparu lors de l'enregistrement";
                Tools::addNotification("error", "Un problème est apparu lors de l'enregistrement");
            }
        };

        $this->template = "article";
        $this->data     = [
            "article" => $post,
            "name" => "",
            "content" => "",
            "comments"=>$comments->getValidsComments($this->request->uri[1])
        ];
    }
    private function contact()
    {
        $this->template = "contact";
        $this->data     = [
            //"test"=>"Miguel"
        ];
    }
    private function mentions_legales()
    {
        $this->template = "mentions-legales";
        $this->data     = [
            //"test"=>"Miguel"
        ];
    }

    private function page404()
    {
        $this->template = "404";
    }
    private function login()
    {
        // si method === POST
        // appel méthode login dans App\Models\Users
        // si c'est juste -> mettre à jour la session + redirection vers  /admin
        // die(var_dump($this->request));
        //Auth::logout();
        if ($this->request->method === "POST") {
            try {
                Auth::login($this->request->post["email"],  $this->request->post["password"]);
                Tools::addNotification("succeed", "Authenfication avec succès");
                //die(var_dump($this->request->session));
                Tools::redirect("/admin");
            } catch (\Throwable $err) {
                Tools::addNotification("error", "T'es sur que c'est toi?");
                // $error = true;
                // $msg   = "un problème est apparu lors de l'enregistrement";
                // die(var_dump($err));
            }
        }
        $this->template = "login";
        $this->data     = [];
    }
}
