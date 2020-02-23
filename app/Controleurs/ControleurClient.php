<?php
declare(strict_types=1);

namespace App\Controleurs;

use App\App;
use App\FilAriane;
use App\Modeles\Adresse;
use App\Modeles\Client;
use App\Utilitaires;
use \DateTime;

class ControleurClient
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

        // Récupérer le contenu des messages en format JSON
        $contenuBruteFichierJson = file_get_contents("liaisons/js/objMessages.json");
        $this->tMessagesJSON = json_decode($contenuBruteFichierJson, true);
    }


    //--------------------------------------------//
    //-------------- PAGE CONNEXION --------------//
    //--------------------------------------------//
    /**
     * Démarre la page pour se connecter
     */
    public function ficheConnexion():void {
        // État de connexion
        //------------------------------------------------//
        if ($this->session->getItem('estConnecte')){
            $etatConnexion = $this->session->getItem('estConnecte');
            $client = $this->session->getItem('client');
        }
        else {
            $etatConnexion = false;
            $client = false;
        }

        // Regrouper les données de la vue
        //------------------------------------------------//
        // Fil d'Ariane
        $filAriane = FilAriane::majFilAriane();

        $tDonnees = array(
            "nomPage"=>"Connexion",
            "etatConnexion"=>$etatConnexion,
            "client"=>$client,
            "messages"=>"off",
            'filAriane' => $filAriane
        );
        echo $this->blade->run("transactions.clients.connecter",$tDonnees);


        // Supprimer le message de rétroaction après avoir créé un compte
        //------------------------------------------------//
        $this->session->supprimerItem('msgConnexion');
        $this->session->supprimerItem('$aDeconnecte');
    }


    //---------------------------------------//
    //-------------- CONNECTER --------------//
    //---------------------------------------//
    /**
     * Fait la validation des champs de formulaire et (si valide)
     * compare avec les enregistrement de la BD, puis
     * redirige vers la page précédente
     */
    public function connecter():void {
        // Aller chercher les champs du form
        //------------------------------------------------//
        // courriel
        if (isset($_POST['courriel'])){
            $courriel = $_POST['courriel'];
        }
        else {$courriel = null;}
        // mot de passe
        if (isset($_POST['mdp'])){
            $mdp = $_POST['mdp'];
        }
        else {$mdp = null;}


        // Validation
        //------------------------------------------------//
        $arrRegex = array(
            'courriel'=>'#^[A-z0-9][A-z0-9\-\.]+@[A-z0-9\-]+([.][A-z]{2,4})+$#',
            'mdp'=>'#(?=.*[a-zà-ÿ])(?=.*[A-ZÀ-Ÿ])(?=.*[0-9]).{8,18}$#');
        $arrValidationCourriel = $this->utilitaires->validerFormulaire('courriel', $courriel, $arrRegex['courriel']);
        $arrValidationMDP = $this->utilitaires->validerFormulaire('mdp', $mdp, $arrRegex['mdp']);

        // Le compte client existe-t-il?
        $estClient = Client::trouverParCourriel($courriel);

        // SI valide, envoie se faire connecter
        //------------------------------------------------//
        if ($arrValidationCourriel['estValide'] == true &&
            $arrValidationMDP['estValide'] == true &&
            $estClient !=null)
        {
            // est-ce dans le mot de passe?
            if (password_verify($mdp, $estClient->mot_de_passe)) {
                // on met/chnage les variables en session
                $this->session->setItem('estConnecte', true);
                $this->session->setItem('client', $estClient);

                // redirection
                if (isset($_GET['pagePrecedente'])) {
                    $pagePrecedente = $this->utilitaires->validerGetPost($_GET['pagePrecedente'], '#^(Accueil|Livres|Fiche|Livraison)$#');
                    if($pagePrecedente == 'Livraison'){
                        // Rediriger vers la livraison
                        header('Location: index.php?controleur=livraison&action=validerConnexion');
                        exit;
                    }
                    else {
                        // Rediriger vers la fiche de connection
                        header('Location: index.php?controleur=client&action=ficheConnexion&pagePrecedente='.$pagePrecedente);
                        exit;
                    }
                }
            }
            // Sinon, il y a des erreurs !
            else {$this->ficheConnexionErreurs();}
        }
        // Sinon, il y a des erreurs !
        else {$this->ficheConnexionErreurs();}
    }


    //----------------------------------------------------------//
    //-------------- FICHE CONNEXION AVEC ERREURS --------------//
    //----------------------------------------------------------//
    /**
     * Démarre la page pour se créer un compte
     */
    public function ficheConnexionErreurs():void {
        $messageConnexion = "";

        // État de connexion
        //------------------------------------------------//
        if ($this->session->getItem('estConnecte')){
            $etatConnexion = $this->session->getItem('estConnecte');
            $client = $this->session->getItem('client');
        }
        else {
            $etatConnexion = false;
            $client = false;
        }

        // Aller chercher les champs du form
        //------------------------------------------------//
        // courriel
        if (isset($_POST['courriel'])){
            $courriel = $_POST['courriel'];
        }
        else {$courriel = null;}
        // mot de passe
        if (isset($_POST['mdp'])){
            $mdp = $_POST['mdp'];
        }
        else {$mdp = null;}

        // Validation
        //------------------------------------------------//
        $arrRegex = array(
            'courriel'=>'#^[A-z0-9][A-z0-9\-\.]+@[A-z0-9\-]+([.][A-z]{2,4})+$#',
            'mdp'=>'#(?=.*[a-zà-ÿ])(?=.*[A-ZÀ-Ÿ])(?=.*[0-9]).{8,18}$#'
        );
        $arrValidationCourriel = $this->utilitaires->validerFormulaire('courriel', $courriel, $arrRegex['courriel']);
        $arrValidationMDP = $this->utilitaires->validerFormulaire('mdp', $mdp, $arrRegex['mdp']);

        // Le compte client existe-t-il?
        $estClient = Client::trouverParCourriel($courriel);


        // msg - est-ce dans le mot de passe?
        if ($estClient !=null) {
            if ($estClient !=null && !password_verify($mdp, $estClient->mot_de_passe)) {
                $messageConnexion = $this->tMessagesJSON['connexion']['mdp'];
                $arrValidationMDP['estValide'] = false;
            }
        }
        // msg - est-ce dans le courriel ?
        if ($estClient == null){
            $messageConnexion = $this->tMessagesJSON['connexion']['courriel'];
            $arrValidationCourriel['estValide'] = false;
        }

        // Regrouper les données de la vue
        //------------------------------------------------//
        $filAriane = FilAriane::majFilAriane();
        $tDonnees = array(
            "nomPage"=>"Creer",
            "etatConnexion"=>$etatConnexion,
            "messages"=>"on",
            "client"=>$client,
            "arrValidationCourriel"=>$arrValidationCourriel,
            "arrValidationMDP"=>$arrValidationMDP,
            "messageConnexion"=>$messageConnexion,
            'filAriane' => $filAriane 
        );
        echo $this->blade->run("transactions.clients.connecter",$tDonnees);
    }


    //-----------------------------------------//
    //-------------- FICHE CRÉER --------------//
    //-----------------------------------------//
    /**
     * Démarre la page pour se créer un compte
     */
    public function ficheCreer():void {
        // État de connexion
        //------------------------------------------------//
        if ($this->session->getItem('estConnecte')){
            $etatConnexion = $this->session->getItem('estConnecte');
        }
        else {
            $etatConnexion = false;
        }

        // Regrouper les données de la vue
        //------------------------------------------------//
        // Fil d'Ariane
        $filAriane = FilAriane::majFilAriane();
        $tDonnees = array(
            "nomPage"=>"Creer",
            "etatConnexion"=>$etatConnexion,
            "messages"=>"off",
            'filAriane' => $filAriane
        );
        echo $this->blade->run("transactions.clients.creer",$tDonnees);
    }


    //-----------------------------------//
    //-------------- CRÉER --------------//
    //-----------------------------------//
    /**
     * Fait la validation des champs de formulaire et (si valide)
     * insère, puis
     * redirige vers la page précédente
     */
    public function creer():void {
        // Toutes les REGEX
        //------------------------------------------------//
        $arrRegex = array(
            'nom'=>'#^[A-ZÀ-Ÿ][a-zA-ZÀ-ÿ( |\-|\')]{1,49}$#',
            'prenom'=>'#^[A-ZÀ-Ÿ][a-zA-ZÀ-ÿ( |\-|\')]{1,29}$#',
            'telephone'=>'#^[0-9]{10}$#',
            'courriel'=>'#^[A-z0-9][A-z0-9\-\.]+@[A-z0-9\-]+([.][A-z]{2,4})+$#',
            'mdp'=>'#(?=.*[a-zà-ÿ])(?=.*[A-ZÀ-Ÿ])(?=.*[0-9]).{8,18}$#'
        );

        // État de connexion
        //------------------------------------------------//
        if ($this->session->getItem('estConnecte')){
            $etatConnexion = $this->session->getItem('estConnecte');
        }
        else {
            $etatConnexion = false;
        }

        // Aller chercher les champs du form
        //------------------------------------------------//
        // nom
        if (isset($_POST['nom'])){
            $nom = $_POST['nom'];
        }else{$nom = null;}
        // prénom
        if (isset($_POST['prenom'])){
            $prenom = $_POST['prenom'];
        }else{$prenom = null;}
        // courriel
        if (isset($_POST['courriel'])){
            $courriel = $_POST['courriel'];
        }else{$courriel = null;}
        // téléphone
        if (isset($_POST['telephone'])){
            $tel = $_POST['telephone'];
        }else{$tel = null;}
        // mot de passe
        if (isset($_POST['mdp'])){
            $mdp = $_POST['mdp'];
        }else{$mdp = null;}


        // Validation
        //------------------------------------------------//
        $arrValidationNom = $this->utilitaires->validerFormulaire('nom', $nom, $arrRegex['nom']);
        $arrValidationPrenom = $this->utilitaires->validerFormulaire('prenom', $prenom, $arrRegex['prenom']);
        $arrValidationCourriel = $this->utilitaires->validerFormulaire('courriel', $courriel, $arrRegex['courriel']);
        $arrValidationTel = $this->utilitaires->validerFormulaire('telephone', $tel, $arrRegex['telephone']);
        $arrValidationMDP = $this->utilitaires->validerFormulaire('mdp', $mdp, $arrRegex['mdp']);
        $dispoCourriel = Client::trouverParCourriel($courriel);

        // Inserertion dans la BD (si tout est valide)
        //------------------------------------------------//
        if ($arrValidationNom['estValide'] == true &&
            $arrValidationPrenom['estValide'] == true &&
            $arrValidationCourriel['estValide'] == true &&
            $arrValidationTel['estValide'] == true &&
            $arrValidationMDP['estValide'] == true &&
            $dispoCourriel == null)
        {
            // Encrypter le mot de passe
            $mdpEncrypter = password_hash($mdp, PASSWORD_DEFAULT);

            // Insérer !
            Client::inserer($prenom, $nom, $courriel, $tel, $mdpEncrypter);

            // Message rétroactif
            $msgConnexion = $this->tMessagesJSON['connexion']['connexion'];
            $this->session->setItem('msgConnexion', $msgConnexion);

            // Redirection
            $this->ficheConnexion();
        }
        else {
            if ($dispoCourriel != null) {
                $arrValidationCourriel['message'] = $this->tMessagesJSON['courriel']['disponible'];
            }

            // Regrouper les données de la vue
            //------------------------------------------------//
            $filAriane = FilAriane::majFilAriane();
            $tDonnees = array(
                "nomPage"=>"Creer",
                "etatConnexion"=>$etatConnexion,
                "messages"=>"on",
                "arrValidationNom"=>$arrValidationNom,
                "arrValidationPrenom"=>$arrValidationPrenom,
                "arrValidationCourriel"=>$arrValidationCourriel,
                "arrValidationTel"=>$arrValidationTel,
                "arrValidationMDP"=>$arrValidationMDP,
                'filAriane' => $filAriane
            );
            echo $this->blade->run("transactions.clients.creer",$tDonnees);

        }
    }


    //-------------------------------------------------------//
    //-------------- DISPONIBILITÉ DU COURRIEL --------------//
    //-------------------------------------------------------//
    /**
     * Vérifie si le courriel est présent dans la BD
     * @return {bool} true: il n'est pas dans la BD! Donc vraiment dispo pour un nouvel utilisateur
     */
    public function dispoCourriel($refCourriel) {
        $client = Client::trouverParCourriel($refCourriel);
        if ($client == null) {
            $estDispo = 1;
        }
        else {
            $estDispo = 0;
        }
        echo $estDispo;
    }


    //-----------------------------------------//
    //-------------- DÉCONNEXION --------------//
    //-----------------------------------------//
    /**
     * Fait la redirection vers la page précédente (si c'est valide avant)
     */
    public function deconnexion():void {

        // Information de la query string
        //------------------------------------------------//
        // etat de connexion
        if ($this->session->getItem('estConnecte')){
            $this->session->supprimerItem('estConnecte');
            $this->session->supprimerItem('client');
        }
        $msgConnexion = $this->tMessagesJSON['connexion']['deconnexion'];
        $this->session->setItem('msgConnexion', $msgConnexion);
        $this->session->setItem('$aDeconnecte', true);
        // Rediriger vers l'accueil
        header('Location: index.php?controleur=site&action=accueil');
        exit;
    }
}
