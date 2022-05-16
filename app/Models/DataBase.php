<?php

namespace App\Models;

class DataBase
{
    public $bdd;

    public function __construct()
    {
        //PDO
        global $superGlobals;
        try {
            $dsn = "mysql:host=localhost;dbname=".$superGlobals->env["DB_DBNAME"];
            $this->bdd = new \PDO($dsn, $superGlobals->env["DB_USER"], $superGlobals->env["DB_PASSWORD"]);
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
