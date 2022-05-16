<?php

namespace App\Ctrl;

class SuperGlobals{
    public function __construct()
    {
        session_start();
        $this->env = $_ENV;
        $this->files = $_FILES;
        $this->session = $_SESSION;
    }
}