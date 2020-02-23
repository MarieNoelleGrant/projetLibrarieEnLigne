<?php
declare(strict_types=1);

namespace App\Controleurs;

use App\App;
use App\Modeles\ModeLivraison;
use App\Utilitaires;
use \DateTime;

class ControleurLivraison
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

    public function validerConnexion() {
        if (isset($_POST["modeLivraison"])) {
            if ($this->validerLivraison($_POST["modeLivraison"])) {
                $modeLivraison = ModeLivraison::trouverParNomModeLivraison($_POST["modeLivraison"]);
                $this->session->setItem("modeLivraison", $modeLivraison);
            }
        } else {
            $modeLivraison = ModeLivraison::trouverParNomModeLivraison("standard");
            $this->session->setItem("modeLivraison", $modeLivraison);
        }
        if ($this->session->getItem("estConnecte") == true) {
            $this->afficher();
        } else {
            header("Location: index.php?controleur=client&action=ficheConnexion&pagePrecedente=Livraison");
            exit;
        }
    }

    public function afficher() {
        // etat de connexion

        if ($this->session->getItem("erreurs") != null) {
            $erreurs = $this->session->getItem("erreurs");
            $this->session->supprimerItem("erreurs");
        } else {
            $erreurs = null;
        }

        $arrValidations = $this->session->getItem("validation");
        $this->session->supprimerItem("validation");
        $tDonnees = array(
            "nomPage" => "Livraison",
            "erreurs" => $erreurs,
            "etatConnexion" => true,
            "validation" => $arrValidations);
        echo $this->blade->run("transactions.commandes.livraison", $tDonnees);
    }

    public function valider() {
        if (isset($_POST['nom'])){
            $nom = $_POST['nom'];
        }
        else {
            $nom = null;
        }
        if (isset($_POST['prenom'])){
            $prenom = $_POST['prenom'];
        }
        else {
            $prenom = null;
        }
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
        if (isset($_POST['defaut'])){
            if ($_POST["defaut"] == 'on' || $_POST["defaut"] == 'off') {
                $defaut = $_POST['defaut'];
            } else {
                $defaut = 'off';
            }
        }
        else {
            $defaut = 'off';
        }
        if (isset($_POST['facturation'])){
            if ($_POST["facturation"] == 'on' || $_POST["facturation"] == 'off') {
                $facturation = $_POST['facturation'];
            } else {
                $facturation = 'off';
            }
        }
        else {
            $facturation = 'off';
        }


        $arrValidationNom = $this->utilitaires->validerFormulaire("nom", $nom,"#^[A-Za-zÀ-ÿ '-]+$#");
        $arrValidationPrenom = $this->utilitaires->validerFormulaire("prenom", $prenom,"#^[A-Za-zÀ-ÿ. '-]+$#");
        $arrValidationAdresse = $this->utilitaires->validerFormulaire("adresse", $adresse, "#^[0-9]+[,'0-9a-zA-ZÀ-ÿ -]+$#");
        $arrValidationVille = $this->utilitaires->validerFormulaire("ville", $ville,"#^[a-zA-ZÀ-ÿ '-]+$#");
        $arrValidationProvinces = $this->utilitaires->validerFormulaire("provinces", $provinces, "#^[A-Z]{2}$#");
        $arrValidationPostal = $this->utilitaires->validerFormulaire("postal", $postal, "#^[A-Za-z][0-9][A-Za-z] [0-9][A-Za-z][0-9]$#");


        if ($arrValidationNom["estValide"] == false ||
            $arrValidationPrenom["estValide"] == false ||
            $arrValidationAdresse["estValide"] == false ||
            $arrValidationVille["estValide"] == false ||
            $arrValidationProvinces["estValide"] == false ||
            $arrValidationPostal["estValide"] == false)
        {
            $tDonnees = array (
                "nom" => $arrValidationNom,
                "prenom" => $arrValidationPrenom,
                "adresse" => $arrValidationAdresse,
                "ville" => $arrValidationVille,
                "provinces" => $arrValidationProvinces,
                "postal" => $arrValidationPostal,
                "defaut" => $defaut,
                "facturation" => $facturation
            );
            $this->session->setItem("erreurs", $tDonnees);
            $this->afficher();
        } else {
            $tDonnees = array (
                "nom" => $arrValidationNom["valeur"],
                "prenom" => $arrValidationPrenom["valeur"],
                "adresse" => $arrValidationAdresse["valeur"],
                "ville" => $arrValidationVille["valeur"],
                "provinces" => $arrValidationProvinces["valeur"],
                "postal" => $arrValidationPostal["valeur"],
                "defaut" => $defaut,
                "facturation" => $facturation
            );

            $this->session->setItem("livraison", $tDonnees);

            header('Location: index.php?controleur=facturation&action=validerConnexion&pagePrecedente=Livraison');
            exit;
        }
    }

    private function validerLivraison(string $unModeLivraison):string {
        $regexValueModeLivraison = '#^(standard|prioritaire|gratuit)$#';
        if (preg_match($regexValueModeLivraison, $unModeLivraison)) {
            $nouveauMode = $unModeLivraison;
        }
        else {
            $nouveauMode = 'null';
        }
        return $nouveauMode;
    }
    

}