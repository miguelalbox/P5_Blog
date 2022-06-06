<?php

namespace Core;

use Core\Sanityze;

class SecurizedRequest
{
    public $uri;
    public $method;
    public $post;
    private $sanitize;
    public $session;

    public function __construct($src)
    {
        $this->method = filter_input(
            INPUT_SERVER,
            "REQUEST_METHOD",
            FILTER_SANITIZE_STRING
        );
        $this->uri = filter_var($_SERVER['REQUEST_URI'], FILTER_SANITIZE_URL, FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH);
        $this->uri = array_slice(explode("/", $this->uri), 1);
        $this->sanitize = new Sanityze($src);

        if ($this->method === "POST") $this->filterPost();
    }

    private function filterPost()
    {
        $this->sanitize->post($_POST);
        $this->post = $this->sanitize->postDataSanitized;
    }

    public function defineMethod($str){
        $tmp = explode("_", $str);
        for ($i = 1; $i <= 10; $i++) {
            $tmp[$i]=ucfirst($tmp[$i]);
        };
        return implode("", $tmp);
    }
}
