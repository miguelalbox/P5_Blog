<?php

namespace App\Models;

use App\Models\DataBase;


class Categories extends DataBase
{
  public $name;
  public $categoryId;

  public function getCategories()
  {
    $req = $this->bdd->prepare("SELECT * FROM `categories` ORDER BY name ASC");
    $req->execute();
    return $req->fetchAll();
  }

  public function getCategoriesHome($start = 0)
  {
    $req = $this->bdd->prepare("SELECT * FROM `categories` ORDER BY id DESC LIMIT :debut, :fin");
    $req->bindValue(":debut", $start, \PDO::PARAM_INT);
    $req->bindValue(":fin", $start + 10, \PDO::PARAM_INT);
    $req->execute();
    $this->listCategorie = $req->fetchAll();
  }

  public function addCategory($newCategory)
  {
    $req = $this->bdd->prepare("INSERT INTO `categories` (`name`) VALUES (:name);");
    $req->bindValue(":name", $newCategory["name"], \PDO::PARAM_STR_CHAR);
    $req->execute();
  }

  public function updateCategory($updateCategory)
  {
    $req = $this->bdd->prepare("UPDATE `categories` SET `name` = :name WHERE `categories`.`id` = :id");
    $req->bindValue(":name", $updateCategory["name"], \PDO::PARAM_STR_CHAR);
    $req->bindValue(":id", $updateCategory["id"], \PDO::PARAM_INT);
    $req->execute();
  }

  public function removeCategory($categoryId)
  {
    $req = $this->bdd->prepare("DELETE FROM `categories` WHERE `categories`.`id` = :id LIMIT 1");
    $req->bindValue(":id", $categoryId["id"], \PDO::PARAM_INT);
    $req->execute();
  }
  
  public function getCategorieInfo($categoryId)
  {
    $req = $this->bdd->prepare("SELECT * FROM `categories` WHERE `id`=:id;");
    $req->bindValue(":id", $categoryId);
    $req->execute();
    $this->hydrate($req->fetch());
  }
}
