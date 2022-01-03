<?php

namespace App\Models;

use App\Models\DataBase;


class Articles extends DataBase{
  public $title;
  public $image;
  public $content;
  public $date;
  public $category;
  public $listPosts;

  /**
   * retourne les 10 articles à partir d'un point de départ
   *
   * @param   Number  [$start]  le point de départ dela pagination
   *
   * @return  void              met à jour la valeur de  $this->listPosts
   */
    public function getTenLastPosts($start=0){
        $req = $this->db->prepare("SELECT * FROM `articles` ORDER BY id DESC LIMIT :debut, :fin");
        $req->bindValue(":debut", $start, \PDO::PARAM_INT);
        $req->bindValue(":fin", $start+10, \PDO::PARAM_INT);
        $req->execute();
        $this->listPosts = $req->fetchAll();
    }


    /**
     * [ajouteArticle description]
     *
     * @param   Array  $nouvelArticle  [$nouvelArticle description]
     * @param   Number  $nouvelArticle["idAuteur]
     *
     * @return  [type]                  [return description]
     */
    public function ajouteArticle($nouvelArticle){  
      if (!isset ($nouvelArticle["idAuteur"] ) ) $nouvelArticle["idAuteur"] = 1; //TODO remove après prise en charge de la session
      // die(var_dump($nouvelArticle));
        $req = $this->db->prepare("INSERT INTO `articles` (`title`, `image`, `content`, `date`, `category`, `id_user`) VALUES (:titre, :image, :contenu, NOW(), :idCategorie, :idAuteur);");
        $req->bindValue(":titre", $nouvelArticle["title"], \PDO::PARAM_STR_CHAR);
        $req->bindValue(":image", $nouvelArticle["image"], \PDO::PARAM_STR_CHAR);
        $req->bindValue(":contenu", $nouvelArticle["content"], \PDO::PARAM_STR_CHAR);
        $req->bindValue(":idCategorie", $nouvelArticle["category"], \PDO::PARAM_INT);
        $req->bindValue(":idAuteur", $nouvelArticle["idAuteur"], \PDO::PARAM_INT);
        $req->execute();
      $this->getTenLastPosts();
    }
    public function updateArticle($updateArticle){
    if (!isset ($updateArticle["idAuteur"] ) ) $updateArticle["idAuteur"] = 1; //TODO remove après prise en charge de la session
      $req = $this->db->prepare("UPDATE `articles` SET `title` = :titre, `image`= :image,  `content`=:contenu, `category`=:idCategorie, `id_user`=:idAuteur WHERE `articles`.`id` = :id");
      $req->bindValue(":titre", $updateArticle["title"], \PDO::PARAM_STR_CHAR);
      $req->bindValue(":image", $updateArticle["image"], \PDO::PARAM_STR_CHAR);
      $req->bindValue(":contenu", $updateArticle["content"], \PDO::PARAM_STR_CHAR);
      $req->bindValue(":idCategorie", $updateArticle["category"], \PDO::PARAM_INT);
      $req->bindValue(":idAuteur", $updateArticle["idAuteur"], \PDO::PARAM_INT);
      $req->bindValue(":id", $updateArticle["id"], \PDO::PARAM_INT);
      $req->execute();
    $this->getTenLastPosts();
    }

    public function getArticleInfo($id){
      $req = $this->db->prepare("SELECT * FROM `articles` WHERE `id`=:id;");
      $req->bindValue(":id", $id);
      $req->execute();
      $this->hydrate($req->fetch());
    }
    
}