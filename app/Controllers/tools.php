<?php

namespace App\Ctrl;

class Tools
{
    static function redirect($newUrl)
    {
        header("Location:$newUrl");
    }

    static function endPage($todo)
    {
        foreach ($todo as $clef => $valeur) {
            SELF::$clef($valeur);
        }
        exit;
    }
}
