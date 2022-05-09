<?php

namespace App\Ctrl;

// use App\Models\Users;
use App\Ctrl\Auth;
use App\Models\Articles;
use App\Ctrl\Tools;
use App\Models\Categorie;
use App\Models\Commentaire;
use PHPMailer\PHPMailer\PHPMailer;
// use PHPMailer\PHPMailer\Exception;


class Front
{
    private $request;
    public $template;
    public $data = [];
    public $current;
    public function __construct($request)
    {
        $this->request                               = $request;
        $fonction                                    = $request->uri[0];
        if ($fonction === "") $fonction                 = "home";
        if (!method_exists($this, $fonction)) $fonction = "page404";
        $this->$fonction();
    }

    private function home()
    {
        $posts = new Articles();
        $posts->getTenLastPosts();
        $categories = new Categorie();
        $categories->getCategoriesHome();


        $this->template = "index";
        $this->data     = [
            "articles"   => $posts->listPosts,
            "categories" => $categories->listCategorie,
        ];
    }

    private function articles()
    {
        $posts = new Articles();
        $posts->getTenLastPosts();
        $categories = new Categorie();
        $categories->getCategoriesHome();

        $this->template = "articles";
        $this->data     = [
            "articles"   => $posts->listPosts,
            "categories" => $categories->listCategorie,
        ];
    }
    private function article()
    {
        $post = new Articles();
        $post->getArticle($this->request->uri[1]);
        $comments = new Commentaire();

        // die(var_dump($comments->getValidsComments($this->request->uri[1])));
        if ($this->request->method === "POST") {
            global $tools;
            try {
                //apppeler model
                $commentaire = new Commentaire();

                $commentaire->addCommentaire([
                    "name" => $this->request->post["name"],
                    "content" => $this->request->post["content"],
                    "articleId" => $this->request->uri[1]
                ]);
                //$msg = "la categorie à bien été enregistré";
                $tools->addNotification("succeed", "le commentaire à bien été enregistrée");
                $tools->redirect("/article/" . $this->request->uri[1]);
            } catch (\Throwable $err) {
                //$error = true;
                //$msg = "un problème est apparu lors de l'enregistrement";
                $tools->addNotification("error", "Un problème est apparu lors de l'enregistrement");
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

    private function categorie()
    {
        $posts = new Articles();
        $categories = new Categorie();
        $categories->getCategoriesHome();
        $this->template = "articles";
        $this->data     = [
            "articles" => $posts->getArticlesFromCategorie($this->request->uri[1]),
            "categories" => $categories->listCategorie,
        ];

        // die(var_dump($this->data));
    }

    private function contact()
    {
        if ($this->request->method === "POST") {
            global $tools;
            try {

                $mail = new PHPMailer();
                $mail->IsSMTP();
                $mail->Mailer="smtp";
                $mail->SMTPDebug=1;
                $mail->SMTPAuth=true;
                $mail->SMTPSecure="tls";
                $mail->Port=587;
                $mail->Host="smtp.gmail.com";
                $mail->Username=$_ENV["EMAIL"];
                $mail->Password=$_ENV["PASSWORD"];

                $mail->IsHTML(true);
                $mail->AddAddress($_ENV["EMAIL"], "Miguel");
                // die(var_dump($this->request->post));
                $mail->AddReplyTo($this->request->post["email"], $this->request->post["first_name"]." ".$this->request->post["last_name"]);
                $mail->Subject="nouveau message depuis le formulaire du site";
                // Le .= nous permet de concatene tout les variable $message
                $message = "De la part de ".$this->request->post['last_name'];
                $message .= " ".$this->request->post['first_name'];
                $message .= "<br>Mail ".$this->request->post['email'];
                $message .=",<br>Tel ".$this->request->post['tel'];
                $message .=",<br>Exprime le besoin suivante: ".$this->request->post['besoin'];
                $mail->MsgHTML($message);
                if (!$mail->Send()){
                    throw $mail;
                }

                // $nom = $_POST['lname'];
                // $prenom = $_POST['fname'];
                // $mail = $_POST['mail'];
                // $tel = $_POST['tel'];
                // $besoin = $_POST['besoin'];
                // afficher le résultat
                //echo '<h3>Informations récupérées en utilisant POST</h3>';
                //echo 'lname : ' . $nom . 'fname:' . $prenom . ' mail : ' . $mail . ' tel : ' . $tel . 'besoin : ' . $besoin;
                //exit;


                // $to = "$mail";
                // $subject = "Contact Blog";
                // $message = wordwrap($message, 70, "r\n");
                // $headers = [
                //     "From" => "miguelsj.pro@gmail.com",
                //     "Reply-To" => "miguelsj.pro@gmail.com",
                //     "Bcc" => "miguelsj.pro@gmail.com",
                // ];

                // mail($to, $subject, $message, $headers);

                //$msg = "le message à bien été enregistré";
                $tools->addNotification("succeed", "Le message à bien été enregistrée, un mail a été envoye dans votre boite mail");
                $tools->redirect("/contact");
            } catch (\Throwable $err) {
                // die(var_dump($err));
                $tools->addNotification("error", "Un problème est apparu lors de l'enregistrement");
            }
        }


        $this->template = "contact";
        $this->data     = [
            //"test"=>"Miguel"
        ];
    }
    private function mentions_legales()
    {
        $this->template = "mentions-legales";
        $this->data     = [
            //"test"=>"Miguel"
        ];
    }

    private function page404()
    {
        $this->template = "404";
    }
    private function login()
    {
        // si method === POST
        // appel méthode login dans App\Models\Users
        // si c'est juste -> mettre à jour la session + redirection vers  /admin
        // die(var_dump($this->request));
        //Auth::logout();
        if ($this->request->method === "POST") {
            global $tools;
            try {
                Auth::login($this->request->post["email"],  $this->request->post["password"]);
                $tools->addNotification("succeed", "Authenfication avec succès");
                //die(var_dump($this->request->session));
                $tools->redirect("/admin");
            } catch (\Throwable $err) {
                $tools->addNotification("error", "T'es sur que c'est toi?");
                // $error = true;
                // $msg   = "un problème est apparu lors de l'enregistrement";
                // die(var_dump($err));
            }
        }
        $this->template = "login";
        $this->data     = [];
    }
}
