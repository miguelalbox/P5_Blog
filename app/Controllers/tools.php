<?php

namespace App\Ctrl;

class Tools
{
    function redirect($newUrl)
    {
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
        global $request;
        $request->session->addNotification($type, $message);
    }
}
