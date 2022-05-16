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
        global $superGlobals;
        $this->data = $superGlobals->session;
        if ( ! isset($this->data["user"])) return;
        if ( ! count($this->data["user"]) >1) return;
        $this->hasSession = true;
    }

    public function setSession($key, $value)
    {
        $this->data[$key] = $value;
        $this->hasSession = true;
        $this->saveSession();
    }

    private function saveSession()
    {
        global $superGlobals;
        foreach ($this->data as $key => $value){
            $superGlobals->session[$key] = $value;
        }
    }

    public function delete(){
        $notifs = $this->getNotifications();
        session_unset();
        $this->data = [
            "notifications" => $notifs
        ];
        $this->hasSession = false;
        $this->saveSession();
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
}
