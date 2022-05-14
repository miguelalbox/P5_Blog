<?php

namespace App\Models;

class DataBase
{
    public $bdd;

    public function __construct()
    {
        //PDO
        try {
            $dsn = "mysql:host=".$_ENV["DB_HOST"].";dbname=".$_ENV["DB_DBNAME"];
            $this->bdd = new \PDO($dsn, $_ENV["DB_USER"], $_ENV["DB_PASSWORD"]);
            $this->bdd->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_ASSOC);
            $this->bdd->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        } catch (\PDOException $e) {
            throw $e->getMessage();
        }
    }

    protected function hydrate($data)
    {
        foreach ($data as $key => $value) {
            $this->$key = $value;
        }
    }
}
