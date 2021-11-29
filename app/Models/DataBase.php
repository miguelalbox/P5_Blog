<?php

namespace App\Models;
class DataBase{

    private $user = "root";
    private $password = "root";
    private $host = "localhost";
    private $dbname = "blog_openclassrooms";
    public $db;

    public function __construct()
    {
        //PDO
        try {
            $dsn = "mysql:host=$this->host;dbname=$this->dbname";
            $this->db = new \PDO($dsn, $this->user, $this->password);
            $this->db->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE,\PDO::FETCH_ASSOC);
            $this->db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

        } catch (\PDOException $e){
            echo $e->getMessage();
        }
    }
}