<?php

declare(strict_types=1);

namespace App;

use App\Controleurs\ControleurClient;
use App\Controleurs\ControleurConfirmation;
use App\Controleurs\ControleurFacturation;
use App\Controleurs\ControleurLivraison;
use App\Controleurs\ControleurSite;
use App\Controleurs\ControleurLivre;
use App\Controleurs\ControleurPanier;
use App\Controleurs\ControleurValidation;
use App\Session\SessionPanier;
use \PDO;
use eftec\bladeone\BladeOne;

/**
 * SUPER GLOBALES -----------------------------------------------------------------------------------------------------
 * $_VARIABLE sont des super globales.
 * $_GET pour la querystring
 * $_POST pour les formulaires
 * $_SERVER pour la documentation du serveur
 * --------------------------------------------------------------------------------------------------------------------
 */

class App
{

    private static $instance = null;
    private $pdo = null;
    private $blade = null;
    private $cookie = null;
    private $session = null;
    private $filAriane = null;
    private $utilitaires = null;

    private function __construct()
    {
    }

    public static function getInstance(): App
    {
        if (App::$instance === null) {
            App::$instance = new App();
        }
        return App::$instance;
    }

    /**
     * Démarre le site
     */
    public function demarrer():void
    {
        // IMPORTANT!! Déclarer le démarrage de la session AVANT tout le reste, sinon ne s'insère pas au bon endroit dans le HTML
        $this->getSession()->demarrer();
        $this->configurerEnvironnement();
        $this->routerLaRequete();
    }

    /**
     * Crée le cookie
     */
    public function getCookie() {
        if ($this->cookie === null) {
            $this->cookie = new Cookie();
        }
        return $this->cookie;
    }

    /**
     * Crée la session
     */
    public function getSession() {
        if ($this->session === null) {
            $this->session = new Session();
        }
        return $this->session;
    }

    /**
     * Crée le panier - faux singleton
     * @return SessionPanier
     */
    public function getSessionPanier():SessionPanier {
        if ($this->session->getItem('panier') == null) {
            $sessionPanier = new SessionPanier();
        }
        else {
            $sessionPanier = $this->session->getItem('panier');
        }
        return $sessionPanier;
    }

    /**
     * Méthode pour configurer l'environnement de travail selon si nous sommes en local ou sur le serveur distant
     * Appelle la méthode getServer()
     */
    private function configurerEnvironnement():void
    {
        if($this->getServeur() === 'serveur-local'){
            error_reporting(E_ALL | E_STRICT);
        }
        date_default_timezone_set('America/Montreal');
    }

    /**
     * Permet de vérifier sur quel serveur le projet est utilisé
     * @return string $env type-serveur
     */
    public function getServeur(): string
    {
        // Vérifier la nature du serveur (local VS production)
        $env = 'null';
        if ((substr($_SERVER['HTTP_HOST'], 0, 9) == 'localhost') ||
            (substr($_SERVER['HTTP_HOST'], 0, 7) == '192.168')  ||
            (substr($_SERVER['SERVER_ADDR'], 0, 7) == '192.168'))
        {
            $env = 'serveur-local';
        } else {
            $env = 'serveur-production';
        }
        return $env;
    }

    /**
     * Permet de vérifier sur quel serveur le projet est utilisé
     * @return {PDO} $this->pdo
     */
    public function getPDO():PDO {

        if ($this->pdo === null) {
            if ($this->getServeur() === 'serveur-local') {
                $leServeur = "localhost";
                $lUtilisateur = "19_displaynonnes";
                $leMotDePasse = "chevalblanc";
                $leNomBD = "19_rpni3_display_nonnes";
            }
            else {
                $leServeur = "localhost";
                $lUtilisateur = "19_displaynonnes";
                $leMotDePasse = "chevalblanc";
                $leNomBD = "19_rpni3_display_nonnes";
            }
            // *** Important de dissocier la classe ConnexionBD et l'objet de type PDO ********************************
            // 1. La classe ConnexionBD sert à établir le contexte de développement
            // 2. L'objet PDO est retournée par la fonction getNouvelleConnexionPDO à partir des informations de ConnexionBD
            // ********************************************************************************************************
            $connexionBD = new ConnexionBD($leServeur, $lUtilisateur, $leMotDePasse, $leNomBD);
            $this->pdo = $connexionBD->getNouvelleConnexionPDO();
        }
        return $this->pdo;
    }

    /**
     * Fait le lien avec blade
     * @return $this->filAriane
     */
    public function getBlade():BladeOne {
        if ($this->blade === null) {
            $cheminDossierVues = "../ressources/vues";
            $cheminDossierCache = '../ressources/cache';
            $this->blade = new BladeOne($cheminDossierVues,$cheminDossierCache,BladeOne::MODE_AUTO);
        }
        return $this->blade;
    }

    public function getFilDAriane():FilAriane {
        if ($this->session->getItem('filAriane') == null) {
            $this->filAriane = new FilAriane();
        }
        else {
            $this->filAriane = $this->session->getItem('filAriane');
        }
        return $this->filAriane;
    }

    public function getUtilitaires():Utilitaires {
        if($this->utilitaires==null) {
            $this->utilitaires = new Utilitaires();
        }
        return $this->utilitaires;
    }

    /**
     * Initialise la requête de page
     */
    public function routerLaRequete():void
    {
        $controleur = null;
        $action = null;

        // Déterminer le controleur responsable de traiter la requête
        if (isset($_GET['controleur'])){
            $controleur = $_GET['controleur'];
        }else{
            $controleur = 'site';
        }

        // Déterminer l'action du controleur
        if (isset($_GET['action'])) {
            $action = $_GET['action'];
        }else {
            $action = 'accueil';
        }

        // Instantier le bon controleur selon la page demandée
        if ($controleur === 'site'){
            $this->monControleur = new ControleurSite();
            switch ($action) {
                case 'accueil':
                    $this->monControleur->accueil();
                    break;
                case 'apropos':
                    $this->monControleur->apropos();
                    break;
                case 'erreur404':
                    $this->monControleur->erreur404();
                    break;
                case 'autocompleter':
                    $this->monControleur->autocompleter();
                    break;
                case 'infoCoupCoeur':
                    $this->monControleur->infoCoupCoeur();
                    break;
                default:
                    header('Location: index.php?controleur=site&action=erreur404');
                    exit;
            }


        }else if ($controleur === 'livre') {
            $this->monControleur = new ControleurLivre();
            switch($action) {
                case 'index':
                    $this->monControleur->index();
                    break;
                case 'fiche':
                    if(isset($_GET['idLivre'])) {
                        $this->monControleur->fiche();
                    }
                    else {
                        header('Location: index.php?controleur=site&action=erreur404');
                        exit;
                    }
                    break;
                default:
                    header('Location: index.php?controleur=site&action=erreur404');
                    exit;
            }


        } else if ($controleur === 'panier') {
            $this->monControleur = new ControleurPanier();
            switch ($action) {
                case 'ajouterItem':
                    $this->monControleur->ajouterItem();
                    break;
                case 'fiche':
                    $this->monControleur->fiche();
                    break;
                case 'majQuantite':
                    $this->monControleur->majQuantite();
                    break;
                case 'supprimerItem':
                    $this->monControleur->supprimerItem();
                    break;
                case 'majModeLivraison':
                    $this->monControleur->majModeLivraison();
                    break;
                default:
                    header('Location: index.php?controleur=site&action=erreur404');
                    exit;
            }


        }else if ($controleur === 'client') {
            $this->monControleur = new ControleurClient();
            switch ($action) {
                case 'ficheConnexion':
                    $this->monControleur->ficheConnexion();
                    break;
                case 'connexion':
                    $this->monControleur->connecter();
                    break;
                case 'ficheCreer':
                    $this->monControleur->ficheCreer();
                    break;
                case 'creer':
                    $this->monControleur->creer();
                    break;
                case 'deconnexion':
                    $this->monControleur->deconnexion();
                    break;
                case 'dispoCourriel':

                    // aller recherche le courriel dans la query
                    if (isset($_GET['valCourriel'])){
                        $courriel = $_GET['valCourriel'];
                    }else{
                        $courriel = '';
                    }

                    // partir la méthode
                    $this->monControleur->dispoCourriel($courriel);
                    break;
                default:
                    header('Location: index.php?controleur=site&action=erreur404');
                    exit;
            }

        }else if ($controleur === 'livraison') {
            $this->monControleur = new ControleurLivraison();
            switch ($action) {
                case 'validerConnexion':
                    $this->monControleur->validerConnexion();
                    break;
                case 'afficher':
                    $this->monControleur->afficher();
                    break;
                case 'valider':
                    $this->monControleur->valider();
                    break;
                default:
                    header('Location: index.php?controleur=site&action=erreur404');
                    exit;
            }


        }else if ($controleur === 'facturation') {
            $this->monControleur = new ControleurFacturation();
            switch ($action) {
                case 'validerConnexion':
                    $this->monControleur->validerConnexion();
                    break;
                case 'afficher':
                    $this->monControleur->afficher();
                    break;
                case 'valider':
                    $this->monControleur->valider();
                    break;
                default:
                    header('Location: index.php?controleur=site&action=erreur404');
                    exit;
            }


        }else if ($controleur === 'validation') {
            $this->monControleur = new ControleurValidation();
            switch ($action) {
                case 'afficher':
                    $this->monControleur->afficher();
                    break;
                case "sauvegarder":
                    $this->monControleur->sauvegarder();
                    break;
                default:
                    header('Location: index.php?controleur=site&action=erreur404');
                    exit;
            }


        }else if ($controleur === 'confirmation') {
            $this->monControleur = new ControleurConfirmation();
            switch ($action) {
                case 'afficher':
                    if($this->session->getItem('panier') == null) {
                        header('Location: index.php?controleur=site&action=accueil');
                        exit;
                    }
                    else {
                        $this->monControleur->afficher();
                    }

                    break;
                default:
                    header('Location: index.php?controleur=site&action=erreur404');
                    exit;
            }


        } else {
            header('Location: index.php?controleur=site&action=erreur404');
            exit;
        }
    }
}