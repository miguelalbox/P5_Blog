<?php

namespace App\Ctrl;

use App\Ctrl\Auth;
use App\Models\Articles;
use App\Models\Categories;
use App\Models\Commentaires;
use PHPMailer\PHPMailer\PHPMailer;


class Front
{
    private $request;
    public $template;
    public $data = [];
    public $current;
    public function __construct($request)
    {
        $this->request                               = $request;
        $fonction                                    = $request->defineMethod( $request->uri[0]);
        if ($fonction === "") $fonction                 = "home";
        if (!method_exists($this, $fonction)) $fonction = "page404";
        $this->$fonction();
    }

    public function home()
    {
        $posts = new Articles();
        $posts->getTenLastPosts();
        $categories = new Categories();
        $categories->getCategoriesHome();


        $this->template = "index";
        $this->data     = [
            "articles"   => $posts->listPosts,
            "categories" => $categories->listCategorie,
        ];
    }

    public function articles()
    {
        $posts = new Articles();
        $posts->getTenLastPosts();
        $categories = new Categories();
        $categories->getCategoriesHome();

        $this->template = "articles";
        $this->data     = [
            "articles"   => $posts->listPosts,
            "categories" => $categories->listCategorie,
        ];
    }
    public function article()
    {
        $post = new Articles();
        $post->getArticle($this->request->uri[1]);
        $comments = new Commentaires();

        if ($this->request->method === "POST") {
            global $framework;
            try {
                $commentaire = new Commentaires();

                $commentaire->addCommentaire([
                    "name" => $this->request->post["name"],
                    "content" => $this->request->post["content"],
                    "articleId" => $this->request->uri[1]
                ]);
                $framework->addNotification("succeed", "le commentaire à bien été enregistrée");
                $framework->redirect("/article/" . $this->request->uri[1]);
            } 
            catch (\Throwable $err) {
                $framework->addNotification("error", "Un problème est apparu lors de l'enregistrement");
            }
        };

        $this->template = "article";
        $this->data     = [
            "article" => $post,
            "name" => "",
            "content" => "",
            "comments" => $comments->getValidsComments($this->request->uri[1])
        ];
    }

    public function categorie()
    {
        $posts = new Articles();
        $categories = new Categories();
        $categories->getCategoriesHome();
        $this->template = "articles";
        $this->data     = [
            "articles" => $posts->getArticlesFromCategorie($this->request->uri[1]),
            "categories" => $categories->listCategorie,
        ];

    }

    public function contact()
    {
        if ($this->request->method === "POST") {
            global $framework;
            try {
                $mail = new PHPMailer();
                $mail->IsSMTP();
                $mail->Mailer = "smtp";
                $mail->SMTPDebug = 1;
                $mail->SMTPAuth = true;
                $mail->SMTPSecure = "tls";
                $mail->Port = 587;
                $mail->Host = "smtp.gmail.com";
                $mail->Username = $framework->env["EMAIL"];
                $mail->Password = $framework->env["PASSWORD"];

                $mail->IsHTML(true);
                $mail->AddAddress($framework->env["EMAIL"], "Miguel");
                $mail->AddReplyTo($this->request->post["email"], $this->request->post["first_name"] . " " . $this->request->post["last_name"]);
                $mail->Subject = "nouveau message depuis le formulaire du site";
                $message = "De la part de " . $this->request->post['last_name'];
                $message .= " " . $this->request->post['first_name'];
                $message .= "<br>Mail " . $this->request->post['email'];
                $message .= ",<br>Tel " . $this->request->post['tel'];
                $message .= ",<br>Exprime le besoin suivante: " . $this->request->post['besoin'];
                $mail->MsgHTML($message);
                if (!$mail->Send()) {
                    throw $mail;
                }

                $framework->addNotification("succeed", "Le message à bien été enregistrée, un mail a été envoye dans votre boite mail");
                $framework->redirect("/contact");
            } 
            catch (\Throwable $err) {
                $framework->addNotification("error", "Un problème est apparu lors de l'enregistrement");
            }
        }


        $this->template = "contact";
        $this->data     = [
        ];
    }
    public function mentionsLegales()
    {
        $this->template = "mentions-legales";
        $this->data     = [
        ];
    }

    public function page404()
    {
        $this->template = "404";
    }
    public function login()
    {
        if ($this->request->method === "POST") {
            global $framework;
            try {
                global $auth;
                $auth->login($this->request->post["email"],  $this->request->post["password"]);
                $framework->addNotification("succeed", "Authenfication avec succès");
                $framework->redirect("/admin/backoffice");
            } 
            catch (\Throwable $err) {
                $framework->addNotification("error", "T'es sur que c'est toi?");
            }
        }
        $this->template = "login";
        $this->data     = [];
    }
}
