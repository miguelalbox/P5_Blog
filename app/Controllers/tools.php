<?php

namespace App\Ctrl;

class Tools
{
    static function redirect($newUrl)
    {
        header("Location:$newUrl");
    }
    
    /**
     * ajoute un message dans la pile des notifications
     *
     * @param   String  $type    le type de notification : "succeed" | "warn" | "error"
     * @param   String  $message la notification
     * @return  void             complete la pile
     */
    static function addNotification($type, $message){
        global $request;
        $request->session->addNotification($type, $message);
    }
}
