<?php

use App\Models\Articles;

namespace App\Ctrl;

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
        if ($fonction === "") $fonction = "backoffice";
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
        $error = false;
        $msg = "";
        if ($this->request->method === "POST") {
            //die(var_dump().var_dump($this->request->post["content"]));
            try {
                //apppeler model
                $article = new Articles();
                $article->ajouteArticle([
                    "title" => $this->request->post["title"],
                    "content" => $this->request->post["content"]
                ]);
                $msg = "l'article à bien été enregistré";
            } catch (\Throwable $err) {
                $error = true;
                $msg="un problème est apparu lors de l'enregistrement";
            }
        };
        $this->template = "backoffice/auteurs";
        $this->data = [
            'menu' => 'auteurs',
            "error" => $error,
            "message" =>$msg
        ];
    }





    private function page404()
    {
        $this->template = "backoffice/404";
    }
}
