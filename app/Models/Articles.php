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
  public $articleId;

  /**
   * retourne les 10 articles à partir d'un point de départ
   *
   * @param   Number  [$start]  le point de départ dela pagination
   *
   * @return  void              met à jour la valeur de  $this->listPosts
   */
    public function getTenLastPosts($start=0){
        $req = $this->bdd->prepare("SELECT * FROM `articles` ORDER BY id DESC LIMIT :debut, :fin");
        $req->bindValue(":debut", $start, \PDO::PARAM_INT);
        $req->bindValue(":fin", $start+10, \PDO::PARAM_INT);
        $req->execute();
        $this->listPosts = $req->fetchAll();
    }

    public function getArticles(){
      $req = $this->bdd->prepare("SELECT * FROM `articles` ORDER BY id ASC");
      $req->execute();
      return $req->fetchAll();
  }
  public function getArticle($getId){
    $req = $this->bdd->prepare("SELECT * FROM `articles` WHERE `articles`.`id` = :id");
    $req->bindValue(":id", $getId, \PDO::PARAM_INT);
    $req->execute();
    $this->hydrate($req->fetch());

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
      // die(var_dump($nouvelArticle));
        $req = $this->bdd->prepare("INSERT INTO `articles` (`title`, `chapo`, `image`, `content`, `date`, `category`, `id_user`) VALUES (:titre, :chapo, :image, :contenu, NOW(), :idCategorie, :idAuteur);");
        $req->bindValue(":titre", $nouvelArticle["title"], \PDO::PARAM_STR_CHAR);
        $req->bindValue(":image", $nouvelArticle["image"], \PDO::PARAM_STR_CHAR);
        $req->bindValue(":contenu", $nouvelArticle["content"], \PDO::PARAM_STR_CHAR);
        $req->bindValue(":idCategorie", $nouvelArticle["category"], \PDO::PARAM_INT);
        $req->bindValue(":idAuteur", $nouvelArticle["idAuteur"], \PDO::PARAM_INT);
        $req->bindValue(":chapo", $nouvelArticle["chapo"], \PDO::PARAM_STR_CHAR);
        $req->execute();
      $this->getTenLastPosts();
    }
    public function updateArticle($updateArticle){
      $req = $this->bdd->prepare("UPDATE `articles` SET `title` = :titre, `chapo` = :chapo, `image`= :image,  `content`=:contenu, `category`=:idCategorie, `id_user`=:idAuteur, `date_update`=NOW() WHERE `articles`.`id` = :id");
      $req->bindValue(":titre", $updateArticle["title"], \PDO::PARAM_STR_CHAR);
      $req->bindValue(":image", $updateArticle["image"], \PDO::PARAM_STR_CHAR);
      $req->bindValue(":contenu", $updateArticle["content"], \PDO::PARAM_STR_CHAR);
      $req->bindValue(":idCategorie", $updateArticle["category"], \PDO::PARAM_INT);
      $req->bindValue(":idAuteur", $updateArticle["idAuteur"], \PDO::PARAM_INT);
      $req->bindValue(":id", $updateArticle["id"], \PDO::PARAM_INT);
      $req->bindValue(":chapo", $updateArticle["chapo"], \PDO::PARAM_STR_CHAR);
      $req->execute();
    $this->getTenLastPosts();
    }

    public function getArticleInfo($articleId){
      $req = $this->bdd->prepare("SELECT * FROM `articles` WHERE `id`=:id;");
      $req->bindValue(":id", $articleId);
      $req->execute();
      $this->hydrate($req->fetch());
    }

    public function removeArticle($articleId){
      $req = $this->bdd->prepare("DELETE FROM `articles` WHERE `articles`.`id` = :id LIMIT 1");
      $req->bindValue(":id", $articleId["id"], \PDO::PARAM_INT);
      $req->execute();
    }

    public function getArticlesFromCategorie($categorieName){
      $req = $this->bdd->prepare("SELECT A.title, A.content, A.chapo, A.date, A.image, A.id FROM articles as A INNER JOIN categories as C ON a.category = C.id  WHERE C.name= :catName ORDER BY A.date");
      $req->bindValue(":catName", $categorieName, \PDO::PARAM_STR);
      $req->execute();
      return $req->fetchAll();
      
    }
    
}