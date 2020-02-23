<?php
declare(strict_types=1);

namespace App\Controleurs;

use App\App;
use App\FilAriane;
use App\Modeles\Categorie;
use App\Modeles\Livre;
use App\Modeles\Utilitaires;
use \DateTime;

class ControleurLivre
{
    // ATTRIBUTS
    private $blade = null;
    private $session = null;
    private $utilitaires = null;

    // MÉTHODES
    public function __construct()
    {
        $this->blade = App::getInstance()->getBlade();
        $this->session = App::getInstance()->getSession();
        $this->utilitaires = App::getInstance()->getUtilitaires();
    }

    /**
     * Démarre la page Catalogue
     */
    public function index(): void
    {
        //Si la personne arrive de "nouveautés" ou "coups de coeur", supprimer catégorie
        if (isset($_GET["special"]) == true && $this->session->getItem("filtre") != null) {
            $this->session->supprimerItem("filtre");
        }


        //Formater le nombre de pages
        if (isset($_POST["nbParPage"])) {
            $nbLivresParPage = $_POST["nbParPage"];
            $this->session->setItem("nbPages", $nbLivresParPage);
        }
        else if ($this->session->getItem("nbPages") != null) {
            $nbLivresParPage = $this->session->getItem("nbPages");
        } else {
            $nbLivresParPage = "8";
        }

        //Savoir à quelle page nous sommes
        $numeroPage = 0;
        if (isset($_GET['page']) == true) {
            $numeroPage = $_GET['page'];
            $this->session->setItem("page", $numeroPage);
        }

        //Vérifier s'il y a un tri
        $tri = "alpha";
        if (isset($_GET['tri']) == true) {
            $tri = $_GET['tri'];
        }

        //Si on clique sur toutes les catégories, enlever la session
        if (isset($_GET['categories']) == true) {
            $this->session->supprimerItem("filtre");
        }

        $arrCategories = Categorie::afficherCategories();

        //Créer la session et compter les livres
        if (isset($_GET['filtre']) == true || isset($_GET["special"]) == true) {
            if (isset($_GET["special"]) == true) {
                if ($_GET['special'] == "coups-coeur") {
                    $nbLivresTotal = Livre::compterCoupsCoeur();
                    $this->session->setItem('special', 'coups-coeur');

                } else if(($_GET['special'] == "nouveautes")) {
                    $nbLivresTotal = Livre::compterNouveautes();
                    $this->session->setItem('special', 'nouveautes');
                }
            } else {
                $this->session->setItem("filtre", (string)$this->utilitaires->validerGetPost($_GET["filtre"], '#^[0-9]+?#'));
                $nbLivresTotal = Livre::compterTri((string)$this->utilitaires->validerGetPost($_GET["filtre"], '#^[0-9]+?#'));
            }
        } else if ($this->session->getItem("filtre") != null) {
            $nbLivresTotal = Livre::compterTri((string)$this->session->getItem("filtre"));
        } else {
            $nbLivresTotal = Livre::compter();
        }


        if (isset($_GET["tri"]) == true) {
            $this->session->setItem("tri", (string)$this->utilitaires->validerGetPost($_GET["tri"], '#^[prix|prix_desc|alpha|alpha_desc]?#'));
        } else if ($this->session->getItem("tri") != null) {
            $tri = $this->session->getItem("tri");
        }

        //Faire les calculs
        $indexCourant = $numeroPage * (int)$nbLivresParPage;
        $nbTotalPages = ceil(($nbLivresTotal / (int)$nbLivresParPage)-1);

        //Faire les filtres ou afficher tous les livres
        if (isset($_GET['filtre']) == true || isset($_GET["special"]) == true) {
            if (isset($_GET["special"]) == true) {
                if ($_GET["special"] == "coups-coeur") {
                    $special = "coups-coeur";
                    $categorieTitre = "Coups de coeur";
                    $arrLivresLimit = Livre::trierCoupsCoeur($tri, $indexCourant, $nbLivresParPage);
                } else {
                    $special = "nouveautes";
                    $categorieTitre = "Nouveautés";
                    $arrLivresLimit = Livre::trierNouveautes($tri, $indexCourant, $nbLivresParPage);
                }
            }
            else {
                $categorie = (string)$this->utilitaires->validerGetPost($_GET["filtre"], '#^[0-9]+?#');
                $categorieTitre = Categorie::trouverParId($categorie);
                $arrLivresLimit = Livre::trierFiltrer($tri, $categorie, $indexCourant, $nbLivresParPage);
                $special = null;
            }
        }
        else if ($this->session->getItem("filtre") != null || $this->session->getItem("special") != null) {
            if ($this->session->getItem("special") == "coups-coeur") {
                $categorieTitre = "Coups de coeur";
                $special = "coups-coeur";
                $arrLivresLimit = Livre::trierCoupsCoeur($tri, $indexCourant, $nbLivresParPage);
            } else if($this->session->getItem("special") == "nouveautes") {
                $categorieTitre = "Nouveautés";
                $special = "nouveautes";
                $arrLivresLimit = Livre::trierNouveautes($tri, $indexCourant, $nbLivresParPage);
            } else {
                $special = null;
                $categorie = $this->session->getItem("filtre");
                $categorieTitre = Categorie::trouverParId((string)$categorie);
                $arrLivresLimit = Livre::trierFiltrer($tri, (string)$categorie, $indexCourant, $nbLivresParPage);
            }
        }
        else {
            $arrLivresLimit = Livre::trier($tri, $indexCourant, $nbLivresParPage);
            $categorie = null;
            $categorieTitre = null;
            $special = null;
        }

        $filAriane = FilAriane::majFilAriane();

        // Aller voir l'état de connexion
        if ($this->session->getItem('estConnecte')){
            $etatConnexion = $this->session->getItem('estConnecte');
        }
        else {
            $etatConnexion = false;
        }

        // Pour avoir la référnece du panier dans la page
        if (isset($_GET['isbn'])) {
            $isbn_livre = (string)$this->utilitaires->validerGetPost($_GET['isbn'], '#^([0-9]{1,5}-)([0-9]{1,7}-)([0-9]{1,6}-)([0-9]{1,3}|[Xx]{1})$#');
        }
        else {
            $isbn_livre="";
        }

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

        // préparer le tableau des valeurs à trandmettre
        $arrDonnees = array(
            "tri" => $tri,
            "livresParPage" => $nbLivresParPage,
            "categorie" => $categorieTitre,
            "nomPage" => "Livres",
            "special" => $special,
            "arrCategories" => $arrCategories,
            "arrLivres" => $arrLivresLimit,
            "numeroPage" => $numeroPage,
            "nombreTotalPages" => $nbTotalPages,
            "urlPagination" => "index.php?controleur=livre&action=index",
            "filAriane" => $filAriane,
            "etatConnexion" => $etatConnexion,
            "messageConfirmationPanier" => $messageConfirmationPanier,
            "refPanierSession" => $refPanierSession,
            "refUnLivre" => $refUnLivre,
            "formatLivre" => 'papier'
        );

        // envoyer à une vue
        echo $this->blade->run("livres.index",$arrDonnees);
    }

    /**
     * Démarre la page Fiche d'un livre
     */
    public function fiche():void {
        if (isset($_GET['idLivre'])) {
            $id_livre= (string)$this->utilitaires->validerGetPost($_GET['idLivre'], '#^[0-9]+?#');
        }
        else {
            $id_livre = "";
        }

        $refUnLivre = Livre::trouverUnLivre($id_livre);
        $nomPage = "Fiche";
        $arrCategories = $refUnLivre->getCategories();
        $arrAuteurs = $refUnLivre->getAuteurs();
        $arrEditeurs = $refUnLivre->getEdition();
        $arrHonneurs = $refUnLivre->getHonneur();
        $arrMentions = $refUnLivre->getMentions();
        $srcImageLivre = $refUnLivre->getSrcAvecISBN();
        $filAriane = FilAriane::majFilAriane();

        $arrDonnees = array(
            "nomPage" => $nomPage,
            "refUnLivre" => $refUnLivre,
            "arrAuteurs" => $arrAuteurs,
            "arrCategories" => $arrCategories,
            "arrEditeurs" => $arrEditeurs,
            "arrHonneurs" => $arrHonneurs,
            "arrMentions" => $arrMentions,
            "srcImageLivre" => $srcImageLivre,
            "filAriane" => $filAriane
        );

        if ($this->session->getItem('panier')!==null) {
            $refPanierSession = $this->session->getItem('panier');
            $messageConfirmationPanier = $refPanierSession->getMessage();
            $refPanierSession->viderMessage();
        }
        else {
            $messageConfirmationPanier = "";
            $refPanierSession = "";
        }

        // Aller voir l'état de connexion
        if ($this->session->getItem('estConnecte')){
            $etatConnexion = $this->session->getItem('estConnecte');
        }
        else {
            $etatConnexion = false;
        }

        if (isset($_GET['formatLivre'])) {
            $formatLivre = (string)$this->utilitaires->validerGetPost($_GET['formatLivre'], '#^(papier|electronique)$#');
        }
        else {
            $formatLivre = 'papier';
        }

        $arrDonnees = array_merge($arrDonnees, array(
            "refPanierSession"=>$refPanierSession,
            "messageConfirmationPanier" => $messageConfirmationPanier,
            "etatConnexion" => $etatConnexion,
            "formatLivre" => $formatLivre
        ));

        echo $this->blade->run("livres.fiche", $arrDonnees);

    }

}
