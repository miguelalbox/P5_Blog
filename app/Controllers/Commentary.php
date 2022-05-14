<?php

namespace App\Ctrl;

use App\Models\Commentaires;

class Commentary{
    private $request;
    public $template;
    public $data = [];
    public $current;
    public function __construct($request)
    {

        // $auth = Auth::login("mikyfiestas@gmail.com", "miguel123");
        // die(var_dump($request->session));

        if (!$request->session->hasSession) {
            global $tools;
            $tools->redirect("/login"); //throw new Error("pas d'utilisateur connecté");
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
                // die(var_dump($this->request->post));
                try {
                    $commentaire->updateCommentaire([
                        "valid" => 1,
                        "id" => $this->request->post["commentId"]
                    ]);
                } catch (\Throwable $err) {
                    //die(var_dump($err));

                    global $tools;
                    $tools->addNotification("error", "echec lors de l'ajout d'un commentaire");
                }
            }
        }

        $commentaireList = $commentaire->getCommentaire();

        //die(var_dump($categories));
        if (count($this->request->uri) === 2) {
            $this->template = "backoffice/commentaires/commentaires";
            $this->data = [
                'menu' => 'commentaires',
                'commentaires' => $commentaireList
            ];
            return;
        }
        //die(var_dump($this->request->uri));
        $fonction = "commentaire_" . $this->request->uri[2];
        // die(var_dump($fonction));
        if (!method_exists($this, $fonction)) $fonction = "page404";
        $this->$fonction();
    }
    public function validatedCommentaire()
    {
    }
    public function commentaire_suprimer()
    {
        $error = false;
        $msg = "";
        global $tools;
        try {
            //apppeler model

            $commentaire = new Commentaires();

            $commentaire->removeCommentaire([
                "id" => $this->request->uri[3]
            ]);
            //die(var_dump($article));

            //$msg = "la categorie à bien été suprimé";
            $tools->addNotification("succeed", "Le commentaire a bien été suprimé");
            $tools->redirect("/admin/commentaires");
        } catch (\Throwable $err) {
            //die(var_dump($err));
            //$error = true;
            //$msg = "un problème est apparu lors de l'enregistrement";
            $tools->addNotification("error", "Un problème est apparu lors de l'enregistrement");
        } finally {
            // die(var_dump($article));
            $this->template = "backoffice/commentaires/commentaires";
            $this->data = [
                'menu' => 'commentaires',
                "error" => $error,
                "message" => $msg,
            ];
        }
    }
}