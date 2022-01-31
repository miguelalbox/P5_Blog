<?php

namespace App\Models;

use App\Models\DataBase;


class Users extends DataBase
{
  public $first_name;
  public $last_name;
  public $email;
  public $password;
  public $civility;
  public $role;
  public $id;

  
  public function ajouteAuteur($newUser)
  {
    $this->addUser($newUser, 3);
  }
   public function modifierAuteur($updateUser)
   {
     $this->updateUser($updateUser);
   }

  /**
   * [addUser description]
   *
   * @param   Object  $newUser  [$newUser description]
   * @param   Number  $role     le role du nouvel utilisateur (1: utilisateur, 2 admin, 3 auteur)
   *
   * @return  void            [return description]
   */

   

  private function addUser($newUser, $role){
    $req = $this->db->prepare("INSERT INTO `users` (`first_name`, `last_name`, `email`, `password`, `civility`, role) VALUES (:first_name, :last_name, :email, :password, :civility, :role);");
    $req->bindValue(":first_name", $newUser["first_name"], \PDO::PARAM_STR_CHAR);
    $req->bindValue(":last_name", $newUser["last_name"], \PDO::PARAM_STR_CHAR);
    $req->bindValue(":email", $newUser["email"], \PDO::PARAM_STR_CHAR);
    $req->bindValue(":password", $newUser["password"], \PDO::PARAM_STR_CHAR);
    $req->bindValue(":civility", $newUser["civility"], \PDO::PARAM_STR_CHAR);
    $req->bindValue(":role", $role, \PDO::PARAM_INT);
    $req->execute();
  }
  
  private function updateUser($updateUser){
    $req = $this->db->prepare("UPDATE `users` SET `last_name` = :last_name, `first_name`= :first_name,  `email`=:email, `password`=:password, `civility`=:civility WHERE `users`.`id` = :id");
    $req->bindValue(":first_name", $updateUser["first_name"], \PDO::PARAM_STR_CHAR);
    $req->bindValue(":last_name", $updateUser["last_name"], \PDO::PARAM_STR_CHAR);
    $req->bindValue(":email", $updateUser["email"], \PDO::PARAM_STR_CHAR);
    $req->bindValue(":password", $updateUser["password"], \PDO::PARAM_STR_CHAR);
    $req->bindValue(":civility", $updateUser["civility"], \PDO::PARAM_STR_CHAR);
    $req->bindValue(":id", $updateUser["id"], \PDO::PARAM_INT);
    $req->execute();
  }
  public function removeUser($id){
    $req = $this->db->prepare("DELETE FROM `users` WHERE `users`.`id` = :id LIMIT 1");
    $req->bindValue(":id", $id["id"], \PDO::PARAM_INT);
    $req->execute();
  }
  

  public function ajouteUtilisateur($newUser){
    $this->addUser($newUser, 1);
  }
  public function modifierUtilisateur($updateUser)
  {
    $this->updateUser($updateUser);
  }

  public function getUserInfo($id){
    $req = $this->db->prepare("SELECT * FROM `users` WHERE `id`=:id;");
    $req->bindValue(":id", $id);
    $req->execute();
    $this->hydrate($req->fetch());
  }
  public function getUsers(){
    $req = $this->db->prepare("SELECT * FROM `users`  WHERE `role`=1 ORDER BY id ASC");
    $req->execute();
    return $req->fetchAll();
}
public function getAuthors(){
  $req = $this->db->prepare("SELECT * FROM `users`  WHERE `role`=3 ORDER BY id ASC");
  $req->execute();
  return $req->fetchAll();
}

  public function login($email){
    $req = $this->db->prepare("SELECT * FROM `users` WHERE `email`=:email LIMIT 1");
    $req->bindValue(":email", $email, \PDO::PARAM_STR_CHAR);
    $req->execute();
    $result = $req->fetch();
    if (!$result) throw ""; // TODO faire la gfestion des erreurs l'eamil n'existe pas dans la base de données
    $this->hydrate($result);
  }
}
