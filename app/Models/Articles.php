<?php

namespace App\Models;

use App\Models\DataBase;


class Articles extends DataBase{
  public $titre;
  public $image;
  public $content;
  public $date;
  public $category;

    public function getAll(){
        // $this->db
    }

}