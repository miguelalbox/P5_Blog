<?php

namespace App\Models;

use App\Ctrl\SuperGlobals;

class DataBase
{
    public $bdd;

    public function __construct()
    {
        //PDO
        try {
            global $framework;
           
            //die(var_dump($framework->env["DB_DBNAME"]));
            $dsn = "mysql:host=localhost;dbname=".$framework->env["DB_DBNAME"];
            $this->bdd = new \PDO($dsn, $framework->env["DB_USER"], $framework->env["DB_PASSWORD"]);
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
