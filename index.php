<?php

require "vendor/autoload.php";

use App\Ctrl\SecurizedRequest;
use App\Ctrl\Back;
use App\Ctrl\Front;


try {
    $request = new SecurizedRequest([
        "post" => [
            "champsA" => FILTER_SANITIZE_ENCODED,
        ]
    ]);
    switch ($request->uri[0]) {
            // case "admin" : 
            //     $page = new Back($request);
            //     break;
            // case "api" : 
            //     $page = new API($request);
            //     break;
        default:
            $page = new Front($request);
            break;
    }
    $loader = new \Twig\Loader\FilesystemLoader(__DIR__."/app/views/");
    $twig = new Twig\Environment($loader,[
        "debug"=>true,
        "cache"=>false
    ]);
    $twig->addExtension(new \Twig\Extension\DebugExtension());
    echo $twig->render($page->template.".twig", $page->data);

    //twig
} catch (\Throwable $err) {
    die(var_dump($err));
}