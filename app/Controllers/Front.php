<?php

namespace App\Ctrl;

// use App\Models\Users;
use App\Ctrl\Auth;
use App\Models\Articles;

class Front
{
    private $request;
    public $template;
    public $data = [];
    public $current;
    public function __construct($request)
    {
        $this->request = $request;
        $fonction = $request->uri[0];
        if ($fonction === "") $fonction = "home";
        if (!method_exists($this, $fonction)) $fonction = "page404";
        $this->$fonction();
    }

    private function home()
    {
        $posts = new Articles();
        $posts->getTenLastPosts();

        $this->template = "index";
        $this->data = [
            "list" => $posts->listPosts
        ];
    }

    private function articles()
    {
        $this->template = "articles";
        $this->data = [
            //"test"=>"Miguel"
        ];
    }
    private function article()
    {
        $this->template = "article";
        $this->data = [
            //"test"=>"Miguel"
        ];
    }
    private function contact()
    {
        $this->template = "contact";
        $this->data = [
            //"test"=>"Miguel"
        ];
    }
    private function mentions_legales()
    {
        $this->template = "mentions-legales";
        $this->data = [
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
                Auth::login($this->request->post["email"],  $this->request->post["password"]);
                //TODO ajoputer un message de réussite de login
                //TODO ajouter la redirection
                
            } catch (\Throwable $err) {
                die(var_dump($err));
                $error = true;
                $msg = "un problème est apparu lors de l'enregistrement";
            }
        }
        $this->template = "login";
        $this->data = [];
    }
}
