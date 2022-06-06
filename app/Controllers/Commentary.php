<?php

namespace App\Ctrl;

use App\Models\Commentaires;

class Commentary
{
    private $request;
    public $template;
    public $data = [];
    public $current;
    public function __construct($request)
    {

        if (!$request->session->hasSession) {
            global $framework;
            $framework->redirect("/login");
        }

        $this->request = $request;
        $fonction = $request->uri[1];
        if ($fonction === "" || !isset($fonction)) $fonction = "backoffice";
        if (!method_exists($this, $fonction)) $fonction = "page404";
        $this->$fonction();
    }
    public function commentaires()
    {

        $commentaire = new Commentaires();
        if ($this->request->method === "POST") {

            if ($this->request->post["action"] === "validate") {
                try {
                    $commentaire->updateCommentaire([
                        "valid" => 1,
                        "id" => $this->request->post["commentId"]
                    ]);
                } 
                catch (\Throwable $err) {

                    global $framework;
                    $framework->addNotification("error", "echec lors de l'ajout d'un commentaire");
                }
            }
        }

        $commentaireList = $commentaire->getCommentaire();

        if (count($this->request->uri) === 2) {
            $this->template = "backoffice/commentaires/commentaires";
            $this->data = [
                'menu' => 'commentaires',
                'commentaires' => $commentaireList
            ];
            return;
        }
        $fonction = "commentaire" . ucfirst($this->request->uri[2]);
        if (!method_exists($this, $fonction)) $fonction = "page404";
        $this->$fonction();
    }
    public function validatedCommentaire()
    {
    }
    public function commentaireSuprimer()
    {
        $error = false;
        $msg = "";
        global $framework;
        try {

            $commentaire = new Commentaires();

            $commentaire->removeCommentaire([
                "id" => $this->request->uri[3]
            ]);

            $framework->addNotification("succeed", "Le commentaire a bien été suprimé");
            $framework->redirect("/admin/commentaires");
        } 
        catch (\Throwable $err) {
            $framework->addNotification("error", "Un problème est apparu lors de l'enregistrement");
        } 
        finally {
            $this->template = "backoffice/commentaires/commentaires";
            $this->data = [
                'menu' => 'commentaires',
                "error" => $error,
                "message" => $msg,
            ];
        }
    }
}
