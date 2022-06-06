<?php

namespace App\Ctrl;

use App\Models\Categories;

class Categorie
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
    public function categories()
    {
        $categories = new Categories();

        $categoriesList = $categories->getCategories();

        if (count($this->request->uri) === 2) {
            $this->template = "backoffice/categories/categories";
            $this->data = [
                'menu' => 'categories',
                'categories' => $categoriesList
            ];
            return;
        }
        $fonction = "categorie" . ucfirst($this->request->uri[2]);

        if (!method_exists($this, $fonction)) $fonction = "page404";
        $this->$fonction();
    }

    public function categorieAjouter()
    {
        $error = false;
        $msg = "";
        if ($this->request->method === "POST") {
            global $framework;
            try {
                $category = new Categories();

                $category->addCategory([
                    "name" => $this->request->post["name"],
                ]);
                $msg = "la categorie à bien été enregistré";
                $framework->addNotification("succeed", "la categorie à bien été enregistrée");
                $framework->redirect("/admin/categories");
            } 
            catch (\Throwable $err) {
                $framework->addNotification("error", "Un problème est apparu lors de l'enregistrement");
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

    public function categorieEditer()
    {
        $error = false;
        $msg = "";
        global $framework;
        try {
            $categorie = new Categories();
            if ($this->request->method === "POST") {
                $categorie->updateCategory([
                    "name" => $this->request->post["name"],
                    "id" => $this->request->uri[3]
                ]);
                $framework->addNotification("succeed", "la categorie à bien été modifié");
                $framework->redirect("/admin/categories");
            }
            $categorie->getCategorieInfo($this->request->uri[3]);
        } 
        catch (\Throwable $err) {
            $framework->addNotification("error", "Un problème est apparu lors de l'enregistrement");
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

    public function categorieSuprimer()
    {
        $error = false;
        $msg = "";
        global $framework;
        try {

            $categorie = new Categories();

            $categorie->removeCategory([
                "id" => $this->request->uri[3]
            ]);
            $framework->addNotification("succeed", "La categorie a bien été suprimé");
            $framework->redirect("/admin/categories");
        } 
        catch (\Throwable $err) {
            $framework->addNotification("error", "Un problème est apparu lors de l'enregistrement");
        } 
        finally {
            $this->template = "backoffice/categories/categories";
            $this->data = [
                'menu' => 'categories',
                "error" => $error,
                "message" => $msg,
            ];
        }
    }
}
