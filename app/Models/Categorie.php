<?php

namespace App\Models;

use App\Models\DataBase;


class Categorie extends DataBase{



    public function getCategories(){
        $req = $this->db->prepare("SELECT * FROM `categories` ORDER BY name ASC");
        $req->execute();
        return $req->fetchAll();
    }

    public function addCategory(){
      
    }
}