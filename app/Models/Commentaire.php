<?php

namespace App\Models;

use App\Models\DataBase;


class Commentaire extends DataBase{
    public $name;
    public $content;



    public function getCommentaire(){
        $req = $this->db->prepare("SELECT * FROM `commentaires` WHERE validated=0 ORDER BY date ASC");
        $req->execute();
        return $req->fetchAll();
    }

    public function getValidsComments($id){
        $req = $this->db->prepare("SELECT * FROM `commentaires` WHERE validated=1 AND `article`=:article ORDER BY date ASC");
        $req->bindValue(":article", $id, \PDO::PARAM_INT);
        $req->execute();
        return $req->fetchAll();
    }

    public function addCommentaire($newComentaire){
        $req = $this->db->prepare("INSERT INTO `commentaires` (`name`, `content`, `date`, `article`) VALUES (:name, :content, NOW(), :article);");
        $req->bindValue(":name", $newComentaire["name"], \PDO::PARAM_STR_CHAR);
        $req->bindValue(":content", $newComentaire["content"], \PDO::PARAM_STR_CHAR);
        $req->bindValue(":article", $newComentaire["articleId"], \PDO::PARAM_INT);
        $req->execute();
      }
      public function updateCommentaire($updateComentaire){
        $req = $this->db->prepare("UPDATE `commentaires` SET `validated` = :estIlValide WHERE `commentaires`.`id` = :id");
        $req->bindValue(":estIlValide", $updateComentaire["valid"], \PDO::PARAM_INT);
        $req->bindValue(":id", $updateComentaire["id"], \PDO::PARAM_INT);
        $req->execute();
      }
      public function removeCommentaire($id){
        $req = $this->db->prepare("DELETE FROM `commentaires` WHERE `commentaires`.`id` = :id LIMIT 1");
        $req->bindValue(":id", $id["id"], \PDO::PARAM_INT);
        $req->execute();
      }
}