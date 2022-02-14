<?php

namespace App\Models;

use App\Models\DataBase;


class Categorie extends DataBase{
    public $name;
    public $id;



    public function getCategories(){
        $req = $this->db->prepare("SELECT * FROM `categories` ORDER BY name ASC");
        $req->execute();
        return $req->fetchAll();
    }
    public function getCategoriesHome($start=0){
      $req = $this->db->prepare("SELECT * FROM `categories` ORDER BY id DESC LIMIT :debut, :fin");
      $req->bindValue(":debut", $start, \PDO::PARAM_INT);
      $req->bindValue(":fin", $start+10, \PDO::PARAM_INT);
      $req->execute();
      $this->listCategorie = $req->fetchAll();
  }

    public function addCategory($newCategory){
        $req = $this->db->prepare("INSERT INTO `categories` (`name`) VALUES (:name);");
        $req->bindValue(":name", $newCategory["name"], \PDO::PARAM_STR_CHAR);
        $req->execute();
      }
      public function updateCategory($updateCategory){
        $req = $this->db->prepare("UPDATE `categories` SET `name` = :name WHERE `categories`.`id` = :id");
        $req->bindValue(":name", $updateCategory["name"], \PDO::PARAM_STR_CHAR);
        $req->bindValue(":id", $updateCategory["id"], \PDO::PARAM_INT);
        $req->execute();
      }
      public function removeCategory($id){
        $req = $this->db->prepare("DELETE FROM `categories` WHERE `categories`.`id` = :id LIMIT 1");
        $req->bindValue(":id", $id["id"], \PDO::PARAM_INT);
        $req->execute();
      }
      public function getCategorieInfo($id){
        $req = $this->db->prepare("SELECT * FROM `categories` WHERE `id`=:id;");
        $req->bindValue(":id", $id);
        $req->execute();
        $this->hydrate($req->fetch());
      }
}