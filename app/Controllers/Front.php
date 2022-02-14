<?php

namespace App\Ctrl;

// use App\Models\Users;
use App\Ctrl\Auth;
use App\Models\Articles;
use App\Ctrl\Tools;
use App\Models\Categorie;

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
        //die(var_dump($post));

        $this->template = "article";
        $this->data     = [
            "article" => $post
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
        // $this->request->session->update("name", "truc")
        // si c'est juste -> mettre à jour la session + redirection vers  /admin
        // die(var_dump($this->request));
        if ($this->request->method === "POST") {
            try {
                Auth:: login($this->request->post["email"],  $this->request->post["password"]);
                //TODO ajoputer un message de réussite de login
                //TODO ajouter la redirection
                Tools:: endPage(["redirect"=>"/admin"]);
                
            } catch (\Throwable $err) {
                die(var_dump($err));
                $error = true;
                $msg   = "un problème est apparu lors de l'enregistrement";
            }
        }
        $this->template = "login";
        $this->data     = [];
    }
}
