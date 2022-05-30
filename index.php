<?php

require "vendor/autoload.php";


use App\Ctrl\Back;
use App\Ctrl\Front;
use Core\Framework;
use App\Ctrl\Author;
use App\Ctrl\User;
use App\Ctrl\Article;
use App\Ctrl\Categorie;
use App\Ctrl\Commentary;
use App\Ctrl\Auth;

try {
    $framework = new Framework([
        "security" => __DIR__."/security.yaml"
    ]);
    $framework->start();
    $auth = new Auth;

    switch ($framework->request->uri[0]) {
        case "admin":
            $page = new Back($framework->request);
            break;
            // case "api" : 
            //     $page = new API($framework->request);
            //     break;
            
        default:
            $page = new Front($framework->request);
            break;
    }
    switch ($framework->request->uri[1]) {
                case "backoffice":
                $page = new Back($framework->request);
                break;
                // case "api" : 
                //     $page = new API($framework->request);
                //     break;
                case "commentaires":
                $page = new Commentary($framework->request);
                break;
                // case "api" : 
                //     $page = new API($framework->request);
                //     break;
                case "categories":
                $page = new Categorie($framework->request);
                break;
                // case "api" : 
                //     $page = new API($framework->request);
                //     break;
                case "articles":
                $page = new Article($framework->request);
                break;
                // case "api" : 
                //     $page = new API($framework->request);
                //     break;
                case "users":
                    $page = new User($framework->request);
                    break;
                    // case "api" : 
                    //     $page = new API($framework->request);
                    //     break;
                case "auteurs":
                    $page = new Author($framework->request);
                    break;
                    // case "api" : 
                    //     $page = new API($framework->request);
                    //     break;

            
                
                default:
                    $page = new Front($framework->request);
                    break;
            }


    //twig
} catch (\Throwable $err) {
    die("index" . var_dump($err));
     //$framework->request->session->addNotification("error", $err);
} finally {
    $loader = new \Twig\Loader\FilesystemLoader(__DIR__ . "/app/views/");
    $twig   = new Twig\Environment($loader, [
        "debug" => true,
        "cache" => false
    ]);
    $twig->addExtension(new \Twig\Extension\DebugExtension());

    $page->data["notifications"] = $framework->getNotifications();

    // if (count($page->data["notifications"]) > 0) die(var_dump($page->data["notifications"]));

    echo $twig->render($page->template . ".twig", $page->data);
}
