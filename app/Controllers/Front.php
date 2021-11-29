<?php

namespace App\Ctrl;

use App\Models\Articles;

class Front{
    private $request;
    public $template;
    public $data = [];
    public $current;
    public function __construct($request){
        $request = $request;
        $fonction = $request->uri[0];
        if($fonction === "") $fonction = "home";
        if (!method_exists($this, $fonction)) $fonction = "page404";
        $this->$fonction();
    }

    private function home(){
        $posts = new Articles();
        $posts->getTenLastPosts();

        $this->template = "index";
        $this->data = [
            "list"=>$posts->listPosts
        ];

    }

    private function articles(){
        $this->template = "articles";
        $this->data = [
            //"test"=>"Miguel"
        ];
        
    }
    private function article(){
        $this->template = "article";
        $this->data = [
            //"test"=>"Miguel"
        ];
        
    }
    private function contact(){
        $this->template = "contact";
        $this->data = [
            //"test"=>"Miguel"
        ];
        
    }
    private function mentions_legales(){
        $this->template = "mentions-legales";
        $this->data = [
            //"test"=>"Miguel"
        ];
        
    }

    private function page404(){
        $this->template = "404";
        
    }
}