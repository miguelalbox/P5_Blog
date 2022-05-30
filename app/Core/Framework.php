<?php

namespace Core;
use Core\SessionManager;
use Core\SecurizedRequest;

class Framework {

    public $auth;
    public $env;
    public $dotenv;
    public $files;
    public $request;
    public $session;
    public $data;
    public function __construct($data)
    {
        session_start();
        
        $this->dotenv = \Dotenv\Dotenv::createImmutable(__DIR__."/../..");
        $this->dotenv->load();
        $this->env = $_ENV;
        $this->files = $_FILES;
        $this->data = $data;
    }
    
    public function start(){
        $this->session = new SessionManager();
        $this->request = new SecurizedRequest($this->data["security"]);
        $this->request->session = &$this->session;
    }

    public function redirect($newUrl)
    {
        // die(".....".var_dump($newUrl));
        header("Location:$newUrl");
        exit;
    }
    
    /**
     * ajoute un message dans la pile des notifications
     *
     * @param   String  $type    le type de notification : "succeed" | "warn" | "error"
     * @param   String  $message la notification
     * @return  void             complete la pile
     */
    function addNotification($type, $message){
        $this->session->addNotification($type, $message);
    }

    function getNotifications(){
        return $this->session->getNotifications();
    }
}