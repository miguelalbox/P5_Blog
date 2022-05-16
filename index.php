<?php

require "vendor/autoload.php";

use App\Ctrl\SecurizedRequest;
use App\Ctrl\Back;
use App\Ctrl\Front;
use App\Ctrl\Tools;
use App\Ctrl\Author;
use App\Ctrl\User;
use App\Ctrl\Article;
use App\Ctrl\Categorie;
use App\Ctrl\Commentary;
use App\Ctrl\Auth;
use App\Ctrl\SuperGlobals;

try {
    $auth = new Auth;
    $superGlobals = new SuperGlobals;
    $tools = new Tools;
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
    switch ($request->uri[1]) {
                case "backoffice":
                $page = new Back($request);
                break;
                // case "api" : 
                //     $page = new API($request);
                //     break;
                case "commentaires":
                $page = new Commentary($request);
                break;
                // case "api" : 
                //     $page = new API($request);
                //     break;
                case "categories":
                $page = new Categorie($request);
                break;
                // case "api" : 
                //     $page = new API($request);
                //     break;
                case "articles":
                $page = new Article($request);
                break;
                // case "api" : 
                //     $page = new API($request);
                //     break;
                case "users":
                    $page = new User($request);
                    break;
                    // case "api" : 
                    //     $page = new API($request);
                    //     break;
                case "auteurs":
                    $page = new Author($request);
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
    //die("index" . var_dump($err));
     $request->session->addNotification("error", $err);
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
