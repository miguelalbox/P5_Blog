<?php

namespace App\Ctrl;

class SessionManager
{
    public $data = [];
    public $hasSession = false;

    public function __construct()
    {
        session_start();
        $this->data = $_SESSION;
        if (count($this->data) >0) $this->hasSession = true;
    }

    public function setSession($key, $value)
    {
        $this->data[$key] = $value;
        $this->hasSession = true;
        $this->saveSession();
    }

    private function saveSession()
    {
        // $_SESSION = $this->data;
        foreach ($this->data as $key => $value){
            $_SESSION[$key] = $value;
        }
    }

    public function delete(){
        session_destroy();
        $this->hasSession = false;
    }
}
