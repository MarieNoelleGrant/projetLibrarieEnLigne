<?php
declare(strict_types=1);

namespace App\Controleurs;

use App\App;
use App\Modeles\Adresse;
use App\Modeles\Client;
use App\Modeles\Commande;
use App\Modeles\ModePaiement;
use App\Session\SessionPanier;
use \DateTime;

class ControleurValidation
{
    private $blade = null;
    private $cookie = null;
    private $session = null;
    private $utilitaires = null;

    public function __construct()
    {
        $this->blade = App::getInstance()->getBlade();
        $this->cookie = App::getInstance()->getCookie();
        $this->session = App::getInstance()->getSession();
        $this->utilitaires = App::getInstance()->getUtilitaires();
    }

    public function afficher() {
        // etat de connexion
        if ($this->session->getItem('estConnecte')){
            $etatConnexion = $this->session->getItem('estConnecte');
        }

        if ($this->session->getItem("livraison")["facturation"] == "on") {
            $memeAdresse = true;
        } else {
            $memeAdresse = false;
        }

        date_default_timezone_set("America/Montreal");
        setlocale(LC_TIME, "fr_CA");
        $nbJourLiv = $this->session->getItem("modeLivraison")->delai_max_jrs;
        $dateEstimee = new DateTime();
        $dateEstimee->modify("+" . $nbJourLiv . " days");
        $jour = (int)$dateEstimee->format('d');
        $mois = (int)$dateEstimee->format('m');
        $annee = (int)$dateEstimee->format('Y');
        $dateCreee = $jour . "/" . $mois . "/" . $annee;

        $tDonnees = array(
            "nomPage" => "Validation",
            "etatConnexion" => $etatConnexion,
            "memeAdresse" => $memeAdresse,
            "dateLivraison" => $dateCreee,
            "refSessionPanier" => $this->session->getItem("panier"),
            "client" => $this->session->getItem("client"),
            "livraison" => $this->session->getItem("livraison"),
            "facturation" => $this->session->getItem("facturation")
        );
        echo $this->blade->run("transactions.commandes.validation", $tDonnees);
    }

    public function sauvegarder() {
        $livraison = $this->session->getItem("livraison");
        $facturation = $this->session->getItem("facturation");


        //**********ADRESSE**********//
        $prenom = $livraison["prenom"];
        $nom = $livraison["nom"];
        $adresse = $livraison["adresse"];
        $ville = $livraison["ville"];
        $province = $livraison["provinces"];
        $postal = strtoupper($livraison["postal"]);
        if ($livraison["defaut"] == "on") {
            $defaut = 1;
        }
        else {
            $defaut = 0;
        }
        $memeAdresse = $livraison["facturation"];

        //*******Même adresse*******
        if($memeAdresse == "on") {
            $idAdresse = Adresse::insererAdresse($prenom, $nom, $adresse, $ville, $postal, $defaut, "livraison", $province);
            $idAdresseFacturation = $idAdresse;
            $idAdresseLivraison = $idAdresse;
        } else {
            $idAdresseLivraison = Adresse::insererAdresse($prenom, $nom, $adresse, $ville, $postal, $defaut, "livraison", $province);
            $adresseFacturation = $facturation["adresse"];
            $villeFacturation = $facturation["ville"];
            $provinceFacturation = $facturation["provinces"];
            $postalFacturation = $facturation["postal"];
            $idAdresseFacturation = Adresse::insererAdresse($prenom, $nom, $adresseFacturation, $villeFacturation, $postalFacturation, 0, "facturation", $provinceFacturation);
        }

        if ($facturation["typeCarte"] == "paypal") {
            $refEstPaypal = "1";
        } else {
            $refEstPaypal = "0";
        }

        switch ($facturation["typeCarte"]) {
            case "visa" :
                $refTypeCarte = "VISA";
                break;
            case "masterCard" :
                $refTypeCarte = "Master Card";
                break;
            case "amex" :
                $refTypeCarte = "American Express";
                break;
            case "paypal" :
                $refTypeCarte = null;
                break;
            default:
                $refTypeCarte = null;
        }

        date_default_timezone_set("America/Montreal");
        setlocale(LC_TIME, "fr_CA");
        $nbJourLiv = $this->session->getItem("modeLivraison")->delai_max_jrs;
        $dateEstimee = new DateTime();
        $dateEstimee->modify("+" . $nbJourLiv . " days");
        $jour = (int)$dateEstimee->format('d');
        $mois = (int)$dateEstimee->format('m');
        $annee = (int)$dateEstimee->format('Y');
        $dateCreee = $annee . "-" . $mois . "-" . $jour;

        //**********MODE DE PAIEMENT**********//
        $nomComplet = $facturation["nom_complet"];
        $refNoCarte = $text=str_replace(' ','',$facturation["numero_carte"]);
        $intNoCarte = (int)$refNoCarte;
        $refDateExp = $facturation["annee_expiration"] . "-" . $facturation["mois_expiration"] . "-01";
        $refCode = $facturation["numero_securite"];
        $refEstDefaut = "1";
        $refIdClient = $this->session->getItem("client")->id;
        $idModePaiement = ModePaiement::insererModePaiement($refEstPaypal, $nomComplet, $intNoCarte, $refTypeCarte, $refDateExp, $refCode, $refEstDefaut, $refIdClient);

        //**********COMMANDE**********//
        $etat = "nouvelle";
        $date = $dateCreee;
        $telephone = $this->session->getItem("client")->telephone;
        $courriel = $this->session->getItem("client")->courriel;
        $adresseClient = $idAdresseFacturation;
        $ModePaiement = $idModePaiement;
        $idTaux = "4";
        $idModeLivraison = $this->session->getItem("modeLivraison")->id_mode_livraison;
        Commande::insererCommande($etat, $date, $telephone, $courriel, $adresseClient, $idTaux, $idModeLivraison, $ModePaiement, $idAdresseFacturation);

        Client::changerAdresseClient($idAdresseFacturation, $this->session->getItem("client")->id);

        //**********SUPPRESSION DES ÉLÉMENTS EN SESSION**********//
//        $this->session->supprimerItem('panier');
//        $this->session->supprimerItem('client');
//        $this->session->supprimerItem('mode_livraison');
//        $this->session->supprimerItem('livraison');
//        $this->session->supprimerItem('livraison');


        header("Location: index.php?controleur=confirmation&action=afficher&pagePrecedente=Validation");
        exit;
    }

}