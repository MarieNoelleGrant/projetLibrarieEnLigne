<?php
declare(strict_types=1);

namespace App\Courriels;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use App\App;


class CourrielConfirmation
{

    private $courriel = null;
    private $blade = null;
    private $cookie = null;
    private $session = null;


    public function __construct(string $unCourriel){

        // Préparer le contenu HTML du courriel
        //------------------------------------------------//
        $unTemps = time();
        $this->blade = App::getInstance()->getBlade();
        $this->cookie = App::getInstance()->getCookie();
        $this->session = App::getInstance()->getSession();

        // État de connexion
        //------------------------------------------------//
        if ($this->session->getItem('estConnecte')) {
            $etatConnexion = $this->session->getItem('estConnecte');
            $client = $this->session->getItem('client');
        } else {
            $etatConnexion = false;
            $client = false;
        }


        // Adresses (facturation et livraison)
        //------------------------------------------------//
        if ($this->session->getItem('facturation')){
            $adresseFacturation = $this->session->getItem("facturation");
        }
        else{$adresseFacturation = null;}

        if ($this->session->getItem('livraison')){
            $adresseLivraison = $this->session->getItem("livraison");

            if ($this->session->getItem("livraison")["facturation"] == "on") {
                $memeAdresse = true;
            } else {
                $memeAdresse = false;
            }
        }
        else{$adresseLivraison = null;}


        // Session panier
        //------------------------------------------------//
        if ($this->session->getItem("panier")){
            $refSessionPanier = $this->session->getItem("panier");
        }
        else{$refSessionPanier = null;}


        // Regrouper les données de la vue
        //------------------------------------------------//
        $tDonnees = array(
            "nomPage"=>"Confirmation",
            "etatConnexion"=>$etatConnexion,
            "client"=>$client,
            "adresseLivraison"=>$adresseLivraison,
            "adresseFacturation"=>$adresseFacturation,
            "memeAdresse"=>$memeAdresse,
            "refSessionPanier"=>$refSessionPanier,
            "temps" => $unTemps
        );

        $unContenuHTML = $this->blade->run("courriels.confirmation", $tDonnees);
        $unContenuHTML_enTexte = 'Le contenu sans HTML... Bonjour!';


        // on crée un new mail -> oui on veut voir les erreurs s'il y en a
        $this->courriel->charSet = 'UTF-8';
        $this->courriel = new PHPMailer(true); // True indique que les exceptions seront lancées (Throwable) et non retourné en valeur retour de la méthode send


        //Configuration du serveur d'envoi
        $this->courriel->SMTPDebug  = 0;                                    // Activer le débogage 0 = off, 1 = messages client, 2 = messages client et serveur
        $this->courriel->isSMTP();                                          // Envoyer le courriel avec le protocole standard SMTP
        $this->courriel->Host       = 'smtp.gmail.com';                     // Adresse du serveur d'envoi SMTP
        $this->courriel->SMTPAuth   = true;                                 // Activer l'authentification SMTP
        $this->courriel->Username   = 'devCourrielToukaya@gmail.com';       // Nom d'utilisateur SMTP
        $this->courriel->Password   = 'patafoin';                  // Mot de passe SMTP
        $this->courriel->SMTPSecure = 'TLS';                                // Activer l'encryption TLS, `PHPMailer::ENCRYPTION_SMTPS` est aussi accepté
        $this->courriel->Port       = 587;                                  // Port TCP à utiliser pour la connexion SMTP

        // Configuration du courriel

        // De:
        $this->courriel->setFrom('info_trace@gmail.com', 'info_trace'); // Définir l'adresse de l'envoyeur.

        // À:
        $this->courriel->addAddress($unCourriel);      // Ajouter l'adresse du destinataire (le nom est optionel)
        //$this->courriel->addReplyTo('info_trace@gmail.com', 'Information');
        //$this->courriel->addCC('cc@example.com');            // Ajouter un destinataire en copie conforme
        //$this->courriel->addBCC('bcc@example.com');          // Ajouter un destinataire caché en copie conforme

        // Fichiers joints:
        //$this->courriel->addAttachment('/var/tmp/file.tar.gz');       // Ajouter un fichier joint
        //$this->courriel->addAttachment('/tmp/image.jpg', 'new.jpg');  // Ajouter un fichier joint avec un nom (Optionel)

        // Contenu:
        $this->courriel->isHTML(true);  // Définir le type de contenu du courriel.
        $this->courriel->Subject = 'Confirmation de votre commande | Librairie Traces';
        $this->courriel->Body    = $unContenuHTML;
        $this->courriel->AltBody = $unContenuHTML_enTexte; // Si le client ne supporte pas le courriels HTML
    }

    public function envoyer():string
    {
        try {
            $this->courriel->send();
            return "Le message a été envoyé.";
        }
        catch (Exception $e) {
            // Gérer les exceptions spécifique à PHPMailer
            return  "Le message ne peut pas être envoyé. (PHPmailer)";

        }
        catch (\Exeception $e) {
            // le \ fait aller à la racine, donc la classe qui a été importé = php
            // Gérer les exeptions internes de PHP
            return "Le message ne peut pas être envoyé. (php)";
        }
    }

}

