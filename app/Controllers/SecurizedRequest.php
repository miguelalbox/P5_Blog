<?php

namespace App\Ctrl;

class SecurizedRequest{
    public $uri;

    public function __construct($rules){
        $this->uri = filter_var($_SERVER['REQUEST_URI'],FILTER_SANITIZE_URL, FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH);
        $this->uri = array_slice(explode("/", $this->uri), 1);
    }
}