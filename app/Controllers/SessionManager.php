<?php

namespace App\Ctrl;

class SessionManager
{
    public $data = [
        "notifications" => []
    ];
    public $hasSession = false;

    public function __construct()
    {
        session_start();
        $this->data = $_SESSION;
        if (count($this->data) >0) {
            $this->hasSession = true;
            $this->extractFromSession();
        };


        // pour le debug 
        // $this->update("id", 14);
        // $this->update("name","Jean Miguel");
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

    public function update($clef, $valeur){
        $this->data[$clef] = $valeur;
        $this->saveSession();
    }

    /**
     * ajoute un message dans la pile des notifications
     *
     * @param   String  $type    le type de notification : "succeed" | "warn" | "error"
     * @param   String  $message la notification
     * @return  void             complete la pile
     */
    public function addNotification($type, $message){
        array_push($this->data["notifications"], ["type"=>$type, "msg"=>$message]);
        $this->saveSession();
    }

    public function getNotifications(){
        $data = $this->data["notifications"];
        $this->data["notifications"] = [];
        $this->saveSession();
        return $data;
    }

    private function extractFromSession(){
        foreach ( $_SESSION as $key => $value){
            $this->data[$key] = $value;
        }
    }
}
