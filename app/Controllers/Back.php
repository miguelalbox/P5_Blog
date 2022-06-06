<?php

namespace App\Ctrl;

use App\Models\Articles;
use App\Models\Commentaires;
use App\Models\Users;

class Back
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

    public function backoffice()
    {
        $articles = new Articles();
        $authors = new Users();
        $comments = new Commentaires();
        $this->template = "backoffice/backoffice";
        $this->data = [
            'menu' => 'backoffice',
            "articles" => $articles->getArticles(),
            "authors" => $authors->getAuthors(),
            "users" => $authors->getUsers(),
            "comments" => $comments->getCommentaire(),
        ];
    }

    public function deconnexion()
    {
        global $framework;
        $this->request->session->delete();
        $framework->addNotification("succeed", "vous êtes bien déconnecté");
        $framework->redirect("/login");
    }

    public function page404()
    {
        $this->template = "backoffice/404";
    }
}
