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

    //super fonction 
    public function test(){
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
        $req = $this->db->prepare("INSERT INTO `articles` (`title`, `image`, `content`, `date`, `category`, `id_user`) VALUES (:titre, :image, :contenu, NOW(), :idCategorie, :idAuteur);");
        $req->bindValue(":titre", $nouvelArticle["title"], \PDO::PARAM_STR_CHAR);
        $req->bindValue(":image", $nouvelArticle["image"], \PDO::PARAM_STR_CHAR);
        $req->bindValue(":content", $nouvelArticle["content"], \PDO::PARAM_STR_CHAR);
        $req->bindValue(":idCategorie", $nouvelArticle["idCategorie"], \PDO::PARAM_INT);
        $req->bindValue(":idAuteur", $nouvelArticle["idAuteur"], \PDO::PARAM_INT);
        $req->execute();
      $this->getTenLastPosts();
    }
    
}