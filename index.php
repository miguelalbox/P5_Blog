<?php

require "vendor/autoload.php";

use App\Ctrl\SecurizedRequest;
use App\Ctrl\Back;
use App\Ctrl\Front;


try {
    
    $dotenv = \Dotenv\Dotenv::createImmutable(__DIR__);
    $dotenv->load();
    $request = new SecurizedRequest(__DIR__."/security.yaml");

    switch ($request->uri[0]) {
        case "admin":
            $page = new Back($request);
            break;
            // case "api" : 
            //     $page = new API($request);
            //     break;
        default:
            $page = new Front($request);
            break;
    }


    //twig
} catch (\Throwable $err) {
    die("index" . var_dump($err));
    // $request->session->addNotification("error", $err);
} finally {
    $loader = new \Twig\Loader\FilesystemLoader(__DIR__ . "/app/views/");
    $twig   = new Twig\Environment($loader, [
        "debug" => true,
        "cache" => false
    ]);
    $twig->addExtension(new \Twig\Extension\DebugExtension());

    $page->data["notifications"] = $request->session->getNotifications();

    //if (count($page->data["notifications"]) > 0) die(var_dump($page->data["notifications"]));

    echo $twig->render($page->template . ".twig", $page->data);
}
