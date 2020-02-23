<?php
declare(strict_types=1);

namespace App\Controleurs;

use App\App;
use App\Modeles\Auteur;
use App\Modeles\Livre;
use App\Modeles\Actualite;
use \DateTime;

class ControleurSite
{
    private $blade = null;
    private $cookie = null;
    private $session = null;
    private $nbVisite = 0;
    private $nbVisiteSession = 0;
    private $utilitaires = null;

    public function __construct()
    {
        $this->blade = App::getInstance()->getBlade();
        $this->cookie = App::getInstance()->getCookie();
        $this->session = App::getInstance()->getSession();
        $this->utilitaires = App::getInstance()->getUtilitaires();

    }


    //-----------------------------------------------//
    //------------------- ACCUEIL -------------------//
    //-----------------------------------------------//
    /**
     * Démarre la page d'accueil
     */
    public function accueil(): void {
        // Aller voir l'état de connexion
        //------------------------------------------------//
        if ($this->session->getItem('estConnecte')){
            $etatConnexion = $this->session->getItem('estConnecte');
        }
        else {
            $etatConnexion = false;
        }

        // Aller chercher les informations de tous les livres
        //------------------------------------------------//
        $arrLivres = Livre::trouverTout();


        // Aller chercher les informations des coups de coeur
        //------------------------------------------------//
        $arrLivresCoupCoeurMobile = Livre::trouverCoupCoeur(3);
        $arrLivresCoupCoeurTable = Livre::trouverCoupCoeur(7);


        // Aller chercher les informations des actualités
        //------------------------------------------------//
        $arrActualites = Actualite::trouverLimit(1, 2);

        // formater la date
        //Pour indiquer fuseau horaire locale (à ajouter au fichier de configuration)
        date_default_timezone_set("America/Montreal");
        setlocale(LC_TIME, "fr_CA");

        foreach ($arrActualites as $actualite) {
            $dateEnCours = new DateTime($actualite->date_actualite);
            $jour = (int)$dateEnCours->format('d');
            $mois = (int)$dateEnCours->format('m');
            $annee = (int)$dateEnCours->format('Y');
            $dateEnCoursMK = mktime(0, 0, 0, $mois, $jour, $annee);
            $actualite->date_actualite = strftime('%A %d %B %Y', $dateEnCoursMK);
        }

        // Pour avoir la référnece du panier dans la page
        //------------------------------------------------//
        if (isset($_GET['isbn'])) {
            $isbn_livre = (string)$this->utilitaires->validerGetPost($_GET['isbn'], '#^([0-9]{1,5}-)([0-9]{1,7}-)([0-9]{1,6}-)([0-9]{1,3}|[Xx]{1})$#');
        }
        else {
            $isbn_livre="";
        }


        // Gestion du panier
        //------------------------------------------------//
        if ($this->session->getItem('panier')!==null) {
            $refPanierSession = $this->session->getItem('panier');
            $messageConfirmationPanier = $refPanierSession->getMessage();
            if ($isbn_livre != "") {
                $refUnLivre = Livre::trouverUnLivreParISBN($isbn_livre);
            }
            else {
                $refUnLivre="";
            }
            $refPanierSession->viderMessage();
        }
        else {
            $messageConfirmationPanier = "";
            $refPanierSession = "";
            $refUnLivre="";
        }

        // Regrouper les données de la vue
        //------------------------------------------------//
        $tDonnees = array(
            "nomPage"=>"Accueil",
            "arrLivresCoupCoeurMobile"=>$arrLivresCoupCoeurMobile,
            "arrLivresCoupCoeurTable"=>$arrLivresCoupCoeurTable,
            "arrLivres"=>$arrLivres,
            "arrActualites"=>$arrActualites,
            "etatConnexion"=>$etatConnexion,
            "messageConfirmationPanier" => $messageConfirmationPanier,
            "refPanierSession" => $refPanierSession,
            "refUnLivre" => $refUnLivre,
            "formatLivre" => 'papier'
        );
        echo $this->blade->run("accueil",$tDonnees);

        // Enlever la rétroaction de déconnexion
        $this->session->supprimerItem('msgConnexion');
        $this->session->supprimerItem('$aDeconnecte');
    }


    //------------------------------------------------//
    //------------------- À PROPOS -------------------//
    //------------------------------------------------//
    /**
     * Démarre la page À propos
     */
    public function apropos():void
    {
        // Aller voir l'état de connexion
        if ($this->session->getItem('estConnecte')){
            $etatConnexion = $this->session->getItem('estConnecte');
        }
        else {
            $etatConnexion = false;
        }

        $tDonnees = array("nomPage"=>"À propos", "etatConnexion"=>$etatConnexion);
        echo $this->blade->run("apropos",$tDonnees);
    }

    //--------------------------------------------------//
    //------------------- ERREUR 404 -------------------//
    //--------------------------------------------------//
    /**
     * Démarre la page d'erreur 404 si une page n'est pas trouvée
     */
    public function erreur404():void
    {
        // Aller voir l'état de connexion
        if ($this->session->getItem('estConnecte')){
            $etatConnexion = $this->session->getItem('estConnecte');
        }
        else {
            $etatConnexion = false;
        }

        $tDonnees = array("nomPage" => "Erreur 404", "etatConnexion"=>$etatConnexion);
        echo $this->blade->run("erreur404", $tDonnees);
    }

    //-----------------------------------------------------------------------//
    //---------------- AJAX - AUTOCOMPLÉTION DE LA RECHERCHE ----------------//
    //-----------------------------------------------------------------------//
    /**
     * Exécute l'autocomplétion de ajax
     * echo (return) la liste de résultats
     */
    public function autocompleter():void {
        $arrAuteurs = Auteur::chercherAuteur((string)$this->utilitaires->validerGetPost($_GET["entree"], "#^[a-zA-ZÀ-ÿ( |\-|')]{1,29}$#"));
        $cpt = 0;
        $resultats = [];
        foreach ($arrAuteurs as $auteur) {
            $resultats[$cpt] = $auteur->prenom_auteur . " " . $auteur->nom_auteur;
            $cpt++;
        }
        $res = "";
        for($cpt = 0; $cpt < count($resultats); $cpt++)
        {
            $res = $res . "<li class='recherche__resultats_item'><a>" . $resultats[$cpt] . "</a></li>";
        }
        echo $res;
    }

    //-----------------------------------------------------------------------------//
    //---------------- AJAX - AFFICHER LES INFO D'UN COUP DE COEUR ----------------//
    //-----------------------------------------------------------------------------//
    /**
     * Exécute la recherche de donnée d'un livre
     * echo (return) un livre en array
     */
    public function infoCoupCoeur():void {
        if (isset($_GET['dataID'])) {
            $id = $_GET['dataID'];
        }
        else {$id='';}

        $livreEnCours = Livre::trouverUnLivre($id);

        // Concaténer les auteurs
        $iAuteur = 0;
        $auteurs = '';
        foreach ($livreEnCours->getAuteurs() as $auteur) {
            if ($iAuteur == 0)
                $auteurs .= $auteur->getPrenomNom();
            else {
                $auteurs .= ', ' . $auteur->getPrenomNom();
            }
            $iAuteur ++;
        }

        // Concaténer les catégories
        $iCat = 0;
        $categories = '';
        foreach ($livreEnCours->getCategories() as $categorie) {
            if ($iCat == 0)
                $categories .= $categorie->nom_fr_categorie;
            else {
                $categories .= ' ' . $categorie->nom_fr_categorie;
            }
            $iCat ++;
        }
        if (strlen($categories) > 60){
            $categoriesRacc = '';
            $categoriesCheck = explode(' ', $categories);
            $categoriesCheck = array_slice($categoriesCheck, 0, 8);
            $categoriesRacc .= implode(' ', $categoriesCheck).' '."[...]";
            $categories = $categoriesRacc;
        }

        $arrInfoCoupCoeur = array(
            "titre"=>$livreEnCours->getTitreFormate(),
            "sous-titre"=>$livreEnCours->sous_titre_livre,
            "auteurs"=>$auteurs,
            "categories"=>$categories,
            "prix"=>$livreEnCours->getPrixFormate($livreEnCours->prix_livre),
            "id"=>$livreEnCours->id_livre
        );
        $strInfoCoupDeCoeur = json_encode($arrInfoCoupCoeur);
        echo $strInfoCoupDeCoeur;
    }
}

