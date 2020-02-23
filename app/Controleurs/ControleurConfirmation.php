<?php
declare(strict_types=1);

namespace App\Controleurs;

use App\App;
use App\Courriels\CourrielConfirmation;
use App\FilAriane;
use \DateTime;

class ControleurConfirmation
{
    private $blade = null;
    private $cookie = null;
    private $session = null;

    public function __construct()
    {
        $this->blade = App::getInstance()->getBlade();
        $this->cookie = App::getInstance()->getCookie();
        $this->session = App::getInstance()->getSession();
    }

    //--------------------------------------------//
    //-------------- PAGE CONNEXION --------------//
    //--------------------------------------------//
    /**
     * Démarre la page pour se connecter
     */
    public function afficher():void
    {
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
            "refSessionPanier"=>$refSessionPanier
        );
        echo $this->blade->run("transactions.commandes.confirmation",$tDonnees);


        // Envoyer le courriel
        //------------------------------------------------//
        $courriel = new CourrielConfirmation($client->courriel);
        $courriel->envoyer();


        // Supprimer le panier de la session (sur le bouton continuer à magasiner?)
        //------------------------------------------------//
        $this->session->supprimerItem('panier');
        $this->session->supprimerItem('modeLivraison');
        $this->session->supprimerItem('livraison');
        $this->session->supprimerItem('facturation');

    }

}