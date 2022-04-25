<?php

namespace App\Ctrl;
use App\Ctrl\SessionManager;
use App\Ctrl\Sanityze;

class SecurizedRequest{
    public $uri;
    public $method;
    public $session;
    public $post;
    private $sanitize; 

    public function __construct($src){
        $this->session = new SessionManager();
        $this->method = filter_input(
            INPUT_SERVER,
            "REQUEST_METHOD",
            FILTER_SANITIZE_STRING
        );
        $this->uri = filter_var($_SERVER['REQUEST_URI'],FILTER_SANITIZE_URL, FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH);
        $this->uri = array_slice(explode("/", $this->uri), 1);
        $this->sanitize = new Sanityze($src);
        // die("///".var_dump($this->sanitize));

        if ($this->method === "POST") $this->filterPost();
    }

    private function filterPost(){
        $this->sanitize->post($_POST);
        $this->post = $this->sanitize->postDataSanitized;
    }
}