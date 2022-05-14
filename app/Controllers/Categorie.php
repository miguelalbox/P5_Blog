<?php

namespace App\Ctrl;

use App\Models\Categories;

class Categorie{
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
    public function categories()
    {
        $categories = new Categories();

        $categoriesList = $categories->getCategories();

        //die(var_dump($categories));
        if (count($this->request->uri) === 2) {
            $this->template = "backoffice/categories/categories";
            $this->data = [
                'menu' => 'categories',
                'categories' => $categoriesList
            ];
            return;
        }
        //die(var_dump($this->request->uri));
        $fonction = "categorie_" . $this->request->uri[2];
        // die(var_dump($fonction));
        if (!method_exists($this, $fonction)) $fonction = "page404";
        $this->$fonction();
    }

    public function categorie_ajouter()
    {
        $error = false;
        $msg = "";
        if ($this->request->method === "POST") {
            //die(var_dump($this->request));
            global $tools;
            try {
                //apppeler model
                $category = new Categories();

                $category->addCategory([
                    "name" => $this->request->post["name"],
                ]);
                $msg = "la categorie à bien été enregistré";
                $tools->addNotification("succeed", "la categorie à bien été enregistrée");
                $tools->redirect("/admin/categories");
            } catch (\Throwable $err) {
                //$error = true;
                //$msg = "un problème est apparu lors de l'enregistrement";
                $tools->addNotification("error", "Un problème est apparu lors de l'enregistrement");
            }
        };
        $this->template = "backoffice/categories/ajouter-modifier-categorie";
        $this->data = [
            'menu' => 'categories',
            "error" => $error,
            "message" => $msg,
            "action" => "Ajouter",
            "title" => "Nouvelle Categorie",
            "first_name" => "",
            "last_name" => "",
            "email" => "",
            "password" => "",
            "civility" => "",
        ];
    }

    public function categorie_editer()
    {
        $error = false;
        $msg = "";
        global $tools;
        try {
            //apppeler model
            $categorie = new Categories();
            if ($this->request->method === "POST") {
                $categorie->updateCategory([
                    "name" => $this->request->post["name"],
                    "id" => $this->request->uri[3]
                ]);
                //$msg = "la categorie à bien été modifié";
                $tools->addNotification("succeed", "la categorie à bien été modifié");
                $tools->redirect("/admin/categories");
            }
            $categorie->getCategorieInfo($this->request->uri[3]);
        } catch (\Throwable $err) {
            //$error = true;
            //$msg = "un problème est apparu lors de l'enregistrement";
            $tools->addNotification("error", "Un problème est apparu lors de l'enregistrement");
        }
        $this->template = "backoffice/categories/ajouter-modifier-categorie";
        $this->data = [
            'menu' => 'categories',
            "error" => $error,
            "message" => $msg,
            "action" => "Modifier",
            "title" => "Modifier Categorie",
            "name" => $categorie->name,

        ];
    }

    public function categorie_suprimer()
    {
        $error = false;
        $msg = "";
        global $tools;
        try {
            //apppeler model

            $categorie = new Categories();

            $categorie->removeCategory([
                "id" => $this->request->uri[3]
            ]);
            //die(var_dump($article));

            //$msg = "la categorie à bien été suprimé";
            $tools->addNotification("succeed", "La categorie a bien été suprimé");
            $tools->redirect("/admin/categories");
        } catch (\Throwable $err) {
            //die(var_dump($err));
            //$error = true;
            //$msg = "un problème est apparu lors de l'enregistrement";
            $tools->addNotification("error", "Un problème est apparu lors de l'enregistrement");
        } finally {
            // die(var_dump($article));
            $this->template = "backoffice/categories/categories";
            $this->data = [
                'menu' => 'categories',
                "error" => $error,
                "message" => $msg,
            ];
        }
    }
}