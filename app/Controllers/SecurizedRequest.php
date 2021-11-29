<?php

namespace App\Ctrl;

class SecurizedRequest{
    public $uri;
    public $method;

    public function __construct($rules){
        $this->method = filter_input(
            INPUT_SERVER,
            "REQUEST_METHOD",
            FILTER_SANITIZE_STRING
        );
        $this->uri = filter_var($_SERVER['REQUEST_URI'],FILTER_SANITIZE_URL, FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH);
        $this->uri = array_slice(explode("/", $this->uri), 1);

        if ($this->method === "POST") $this->filterPost($rules["post"]);
    }

    private function filterPost($rules){
        $this->post = filter_input_array(
            INPUT_POST,
            $rules
        );
    }
}