<?php
declare(strict_types=1);

namespace App\Controleurs;

use App\App;
use \DateTime;
use App\Utilitaires;

class ControleurFacturation
{
    private $blade = null;
    private $cookie = null;
    private $session = null;
    private $utilitaires = null;
    private $tMessagesJSON = null;

    public function __construct()
    {
        $this->blade = App::getInstance()->getBlade();
        $this->cookie = App::getInstance()->getCookie();
        $this->session = App::getInstance()->getSession();
        $this->utilitaires = App::getInstance()->getUtilitaires();

        $contenuBruteFichierJson = file_get_contents("liaisons/js/objMessages.json");
        $this->tMessagesJSON = json_decode($contenuBruteFichierJson, true); // Convertir en tableau associatif
    }

    public function validerConnexion() {
        if ($this->session->getItem("client") != null) {
            $this->afficher();
        } else {
            header("Location: index.php?controleur=client&action=ficheConnexion&pagePrecedente=Facturation");
            exit;
        }
    }

    public function afficher() {
        // etat de connexion
        if ($this->session->getItem('estConnecte')){
            $etatConnexion = $this->session->getItem('estConnecte');
        }
        else {
            $etatConnexion = false;
        }
        if ($this->session->getItem("erreurs") != null) {
            $erreurs = $this->session->getItem("erreurs");
            $this->session->supprimerItem("erreurs");
        } else {
            $erreurs = null;
        }

        if ($this->session->getItem("livraison")["facturation"] == "on") {
            $memeAdresse = true;
        } else {
            $memeAdresse = false;
        }
        $arrValidations = $this->session->getItem("validation");
        $this->session->supprimerItem("validation");
        $tDonnees = array(
            "nomPage" => "Facturation",
            "memeAdresse" => $memeAdresse,
            "coordonneesAdresse" => $this->session->getItem("livraison"),
            "erreurs" => $erreurs,
            "client" => $this->session->getItem("client"),
            "etatConnexion" => $etatConnexion,
            "validation" => $arrValidations);
        echo $this->blade->run("transactions.commandes.facturation", $tDonnees);
    }

    public function valider() {
        if (isset($_POST['typePaiement'])){
            $typePaiement = $_POST['typePaiement'];
        } else {
            $typePaiement = null;
        }
        if (isset($_POST['nom_complet'])){
            $nom = $_POST['nom_complet'];
        } else {
            $nom = null;
        }
        if (isset($_POST['numero_carte'])){
            $numCarte = $_POST['numero_carte'];
        } else {
            $numCarte = null;
        }
        if (isset($_POST['numero_securite'])){
            $codeSec = $_POST['numero_securite'];
        } else {
            $codeSec = null;
        }
        if (isset($_POST['mois_expiration'])){
            $mois = $_POST['mois_expiration'];
        } else {
            $mois = null;
        }
        if (isset($_POST['annee_expiration'])){
            $annee = $_POST['annee_expiration'];
        } else {
            $annee = null;
        }

        if ($typePaiement == "paypal" || $typePaiement == "visa" || $typePaiement == "masterCard" || $typePaiement == "amex") {
            $arrValidationTypeCarte = ["valeur" => $typePaiement, "message" => "", "estValide" => true];
        } else {
            $arrValidationTypeCarte = ["valeur" => $typePaiement, "message" => $this->tMessagesJSON["typePaiement"]['vide'], "estValide" => false];
        }
        if ($typePaiement == "paypal") {
            $arrValidationNumero = $this->utilitaires->validerFormulaire("numero_carte", $numCarte,"#^[0-9]{4}( )?[0-9]{4}( )?[0-9]{4}( )?[0-9]{4}$#");
        } else if ($typePaiement == "visa") {
            $arrValidationNumero = $this->utilitaires->validerFormulaire("numero_carte", $numCarte,"#^4[0-9]{3}( )?[0-9]{4}( )?[0-9]{4}( )?[0-9]{4}$#");
        } else if ($typePaiement == "masterCard") {
            $arrValidationNumero = $this->utilitaires->validerFormulaire("numero_carte", $numCarte,"#^5[0-9]{3}( )?[0-9]{4}( )?[0-9]{4}( )?[0-9]{4}$#");
        } else {
            $arrValidationNumero = $this->utilitaires->validerFormulaire("numero_carte", $numCarte,"#^[13][0-9]{3}( )?[0-9]{4}( )?[0-9]{4}( )?[0-9]{4}$#");
        }
        $arrValidationNomCarte = $this->utilitaires->validerFormulaire("nom_complet", $nom,"#^[A-Za-zÀ-ÿ '-]+$#");
        $arrValidationCode = $this->utilitaires->validerFormulaire("numero_securite", $codeSec,"#^[0-9]{3}$#");
        $arrValidationMois = $this->utilitaires->validerFormulaire("mois_expiration", $mois,"#^[0-9]{2}$#");
        $arrValidationAnnee = $this->utilitaires->validerFormulaire("annee_expiration", $annee, "#^[0-9]{4}$#");

        if ($annee != "" && $mois != "") {
            $dateButoir = new DateTime($annee . "-" . $mois . "-01");
            $dateAujourdHui = new DateTime();
            if ($dateAujourdHui<$dateButoir) {
                $arrValidationDateComplete = ["valeur" => $dateButoir, "message" => "", "estValide" => true];
            } else {
                $arrValidationDateComplete = ["valeur" => $dateButoir, "message" => $this->tMessagesJSON["dateComplete"]["motif"], "estValide" => false];
            }
        } else {
            $arrValidationDateComplete = ["estValide" => false];
        }

        //SI ADRESSES DIFFÉRENTES
        $livraison = $this->session->getItem("livraison");
        if($livraison["facturation"] == "off") {
            if (isset($_POST['adresse'])){
                $adresse = $_POST['adresse'];
            }
            else {
                $adresse = null;
            }
            if (isset($_POST['ville'])){
                $ville = $_POST['ville'];
            }
            else {
                $ville = null;
            }
            if (isset($_POST['provinces'])){
                $provinces = $_POST['provinces'];
            }
            else {
                $provinces = null;
            }
            if (isset($_POST['postal'])){
                $postal = $_POST['postal'];
            }
            else {
                $postal = null;
            }
            $arrValidationAdresse = $this->utilitaires->validerFormulaire("adresse", $adresse, "#^[0-9]+[,0-9'a-zA-ZÀ-ÿ -]+$#");
            $arrValidationVille = $this->utilitaires->validerFormulaire("ville", $ville,"#^[a-zA-ZÀ-ÿ '-]+$#");
            $arrValidationProvinces = $this->utilitaires->validerFormulaire("provinces", $provinces, "#^[A-Z]{2}$#");
            $arrValidationPostal = $this->utilitaires->validerFormulaire("postal", $postal, "#^[A-Za-z][0-9][A-Za-z]( )?[0-9][A-Za-z][0-9]$#");

            if( $arrValidationTypeCarte["estValide"] == false ||
                $arrValidationNomCarte["estValide"] == false ||
                $arrValidationNumero["estValide"] == false ||
                $arrValidationCode["estValide"] == false ||
                $arrValidationMois["estValide"] == false ||
                $arrValidationAnnee["estValide"] == false ||
                $arrValidationDateComplete["estValide"] == false ||
                $arrValidationAdresse["estValide"] == false ||
                $arrValidationVille["estValide"] == false ||
                $arrValidationProvinces["estValide"] == false ||
                $arrValidationPostal["estValide"] == false){

                $tDonnees = array(
                  "typeCarte" => $arrValidationTypeCarte,
                  "nom_complet" => $arrValidationNomCarte,
                  "numero_carte" => $arrValidationNumero,
                  "numero_securite" => $arrValidationCode,
                  "mois_expiration" => $arrValidationMois,
                  "annee_expiration" => $arrValidationAnnee,
                  "dateComplete" => $arrValidationDateComplete,
                  "adresse" => $arrValidationAdresse,
                  "ville" => $arrValidationVille,
                  "provinces" => $arrValidationProvinces,
                  "postal" => $arrValidationPostal
                );
                $this->session->setItem("erreurs", $tDonnees);
                $this->afficher();
            } else {
                $tDonnees = array(
                    "typeCarte" => $arrValidationTypeCarte["valeur"],
                    "nom_complet" => $arrValidationNomCarte["valeur"],
                    "numero_carte" => $arrValidationNumero["valeur"],
                    "numero_securite" => $arrValidationCode["valeur"],
                    "mois_expiration" => $arrValidationMois["valeur"],
                    "annee_expiration" => $arrValidationAnnee["valeur"],
                    "dateComplete" => $arrValidationDateComplete["valeur"],
                    "adresse" => $arrValidationAdresse["valeur"],
                    "ville" => $arrValidationVille["valeur"],
                    "provinces" => $arrValidationProvinces["valeur"],
                    "postal" => $arrValidationPostal["valeur"]
                );
                $this->session->setItem("facturation", $tDonnees);

                header('Location: index.php?controleur=validation&action=afficher&pagePrecedente=Facturation');
                exit;
            }

            //SI MÊME ADRESSE
        } else {
            if( $arrValidationTypeCarte["estValide"] == false ||
                $arrValidationNomCarte["estValide"] == false ||
                $arrValidationNumero["estValide"] == false ||
                $arrValidationCode["estValide"] == false ||
                $arrValidationMois["estValide"] == false ||
                $arrValidationAnnee["estValide"] == false ||
                $arrValidationDateComplete["estValide"] == false) {

                $tDonnees = array(
                    "typeCarte" => $arrValidationTypeCarte,
                    "nom_complet" => $arrValidationNomCarte,
                    "numero_carte" => $arrValidationNumero,
                    "numero_securite" => $arrValidationCode,
                    "mois_expiration" => $arrValidationMois,
                    "annee_expiration" => $arrValidationAnnee,
                    "dateComplete" => $arrValidationDateComplete
                );
                $this->session->setItem("erreurs", $tDonnees);
                $this->afficher();
            } else {
                $tDonnees = array(
                    "typeCarte" => $arrValidationTypeCarte["valeur"],
                    "nom_complet" => $arrValidationNomCarte["valeur"],
                    "numero_carte" => $arrValidationNumero["valeur"],
                    "numero_securite" => $arrValidationCode["valeur"],
                    "mois_expiration" => $arrValidationMois["valeur"],
                    "annee_expiration" => $arrValidationAnnee["valeur"],
                    "dateComplete" => $arrValidationDateComplete["valeur"]
                );
                $this->session->setItem("facturation", $tDonnees);

                header('Location: index.php?controleur=validation&action=afficher&pagePrecedente=Facturation');
                exit;
            }
        }

    }

}