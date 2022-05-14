<?php

namespace App\Ctrl;

// use App\Ctrl\SanizedField;
use Symfony\Component\Yaml\Yaml;
use Symfony\Component\Yaml\Exception\ParseException;
// use ValueError;

class Sanityze
{
  private $body             = [];
  private $filters          = [];
  public $postDataErrors    = [];
  public $postDataModified  = [];
  public $postDataSanitized = [];

  public function __construct($file)
  {
    try {
      // die(var_dump($file));
      // $data = yaml_parse_file($file); //à utiliser à la place des imports symfony si le module est activé sur votre server
      $data = Yaml::parseFile($file);

      if (isset($data["body"])) $this->body       = $data["body"];
      if (isset($data["filters"])) $this->filters = $data["filters"];
    } catch (ParseException $exception) {
      //die("error parse security YAML".var_dump($exception->getMessage));
      global $tools;
      $tools->addNotification("error", "error parse security YAML");
    }
  }

  private function sanityzeFied($filedName, $value)
  {
    $todo = $this->filters[$this->body[$filedName]]["sanitize"] ?? null;
    if (is_null($todo)) {
      global $tools;
      $tools->addNotification("error", "la regle de nettoyage " . $filedName . " n'existe pas");
      return;
    }
    foreach ($todo as $cleaner) {
      if (gettype($cleaner) === "array") {
        if ($cleaner["regex"]) $value = preg_replace($cleaner["regex"], "", $value);
      }
      if (gettype($cleaner) === "string") {
        if (!method_exists($this, $cleaner)) {
          global $tools;
          $tools->addNotification("error", "la methode de nettoyage " . $cleaner . " n'existe pas");
          return;
        }
        $value = $this->$cleaner($value);
      }
    }
    return $value;
  }

  public function post($data)
  {
    foreach ($data as $inputName => $value) {
      if (!isset($this->body[$inputName])) continue;
      $this->postDataSanitized[$inputName] = $this->sanityzeFied($inputName, $value);
      $ref = $this->body[$inputName];
      if (isset($this->filters[$ref]["valid"])) {
        $isValid = preg_match_all($this->filters[$ref]["valid"], $value);
        if (!$isValid) array_push($this->postDataErrors, $inputName);
      }
      if ($data[$inputName] !== $this->postDataSanitized[$inputName]) array_push($this->postDataModified, $inputName);
      $this->postDataSanitized[$inputName] = trim($this->postDataSanitized[$inputName]);
    };
    return $this->postDataSanitized;
  }

  public function isValid($inputName)
  {
    return in_array($inputName, $this->postDataErrors);
  }

  public function hasBeenModified($inputName)
  {
    return in_array($inputName, $this->postDataModified);
  }

  public function safeOutput($value)
  {
    return htmlentities($value, ENT_COMPAT, 'utf-8');
  }

  private function avoidSqlInjection($value)
  {
    return str_replace("`", "", $value);
  }

  // private function laTotale($value){
  //   $value = $this->avoidSqlInjection($value);
  //   return $this->safeOutput($value);
  // }
}
