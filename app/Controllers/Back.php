<?php

namespace App\Ctrl;

// use App\Ctrl\Auth;
// use App\Ctrl\Tools;
// use Error;

class Back
{
    private $request;
    public $template;
    public $data = [];
    public $current;
    public function __construct($request)
    {

        // $auth = Auth::login("mikyfiestas@gmail.com", "miguel123");
        //die(var_dump($request));
        
        if (!$request->session->hasSession) {
            global $framework;
            $framework->redirect("/login"); //throw new Error("pas d'utilisateur connecté");
        }

        $this->request = $request;
        $fonction = $request->uri[1];
        if ($fonction === "" || !isset($fonction)) $fonction = "backoffice";
        if (!method_exists($this, $fonction)) $fonction = "page404";
        $this->$fonction();
    }

    public function backoffice()
    {
        $this->template = "backoffice/backoffice";
        $this->data = [
            'menu' => 'backoffice'
        ];
    }

    public function deconnexion()
    {
        global $framework;
        $this->request->session->delete();
        $framework->addNotification("succeed", "vous êtes bien déconnecté");
        $framework->redirect("/login");
    }

    public function page404()
    {
        $this->template = "backoffice/404";
    }
}
