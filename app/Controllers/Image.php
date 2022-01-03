<?php

namespace App\Ctrl;



class Image {

    private $valide = true;
    private $name;
    private $ruta;
    private $routeRelative = "public/images/";


  public function __construct($image)
  {


    // array(1) { ["image"]=> array(5) { ["name"]=> string(21) "tmp_1621329767177.jpg" ["type"]=> string(10) "image/jpeg" ["tmp_name"]=> string(45) "C:\Users\Miguel\AppData\Local\Temp\phpA49.tmp" ["error"]=> int(0) ["size"]=> int(731879) } }


    $nouvelleImage = $image["image"];

    $this->valide = $this->validImage($nouvelleImage);
    // die(var_dump($this->valide));

    //ce n'est pas valide on ne va pas plus loin
    if (!$this->valide) return;

    $this->name = $this->rename($nouvelleImage["name"], $nouvelleImage["type"]);
    // die(var_dump($this->name));
    // die(var_dump($nouvelleImage).var_dump($this->getPath()));


    if ( ! move_uploaded_file($nouvelleImage['tmp_name'], $this->getPath())) {
      $this->valide = false;
    }
  }
  
  private function updateName(){

  }
  public function isValid(){
      return $this->valide;
  }

  private function validImage($image){

    // le type défini
    if ($image["type"] === "") return false;

    // on verifie l'extension
    $extensions = array(0=>'image/jpg',1=>'image/jpeg',2=>'image/png');
    if ( !in_array($image['type'], $extensions) ) return false;

    // onverifie la taille
    $max_size = 1024 * 1024 * 8;
    if (  $image['size'] > $max_size ) return false;

    // a passé tous les tests
    return true;
  }

  private function rename($name, $extension){
      $ext = explode("/", $extension)[1];
      return substr($name, 0, 5).date("Y-m-d_hisa").".".$ext;
  }

  public function getPath(){
    return __DIR__."/../../public/images/".$this->name;
  }

  public function getRelativePath(){
    $path = substr($this->routeRelative, strlen("public"));
    return $path.$this->name;
  }

}