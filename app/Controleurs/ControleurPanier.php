<?php
declare(strict_types=1);

namespace App\Controleurs;

use App\App;
use App\FilAriane;
use App\Utilitaires;
use App\Modeles\Livre;
use App\Session\SessionPanier;
use DateTime;

class ControleurPanier {
    private $blade = null;
    private $session = null;
    private $sessionPanier = null;
    private $utilitaires = null;
//    private $filAriane = null;

    public function __construct() {
        $this->blade = App::getInstance()->getBlade();
        $this->session = App::getInstance()->getSession();
        $this->sessionPanier = App::getInstance()->getSessionPanier();
        $this->utilitaires = App::getInstance()->getUtilitaires();
//        $this->filAriane = App::getUtilitairesetInstance()->getFilDAriane();
    }

    /**
     * Permet d'ajouter un item dans le panier
     */
    public function ajouterItem():void {

        // Validation de ce qui se trouve dans le $_GET ou le $_POST. Appellent méthodes
        if(isset($_GET['isbn'])) {
            $isbn_livre = (string)$this->utilitaires->validerGetPost($_GET['isbn'], '#^([0-9]{1,5}-)([0-9]{1,7}-)([0-9]{1,6}-)([0-9]{1,3}|[Xx]{1})$#');
        }
        else {
            $isbn_livre="";
        }

        if(isset($_POST['choixQte'])) {
            $qteLivre = (int)$this->utilitaires->validerGetPost($_POST['choixQte'],'#^([1-9]|10)$#');
        }
        else {
            $qteLivre = 1;
        }

        if (isset($_POST['formatLivre'])) {
            $formatLivre = (string)$this->utilitaires->validerGetPost($_POST['formatLivre'], '#^(papier|electronique)$#');
        }
        else {
            $formatLivre = 'papier';
        }

        if(isset($_GET['nomPage'])) {
            $nomPage = (string)$this->utilitaires->validerGetPost($_GET['nomPage'], '#^(Accueil|Livres|Fiche)$#');
        }
        else {
            $nomPage = 'Accueil';
        }


        $ajoutQuerySiIndexLivre = "";
        if(isset($_GET['filtre'])) {
            $ajoutQuerySiIndexLivre .= '&filtre='.$_GET['filtre'];
        }
        elseif ($this->session->getItem('filtre')!=null) {
            $ajoutQuerySiIndexLivre .= '&filtre='.$this->session->getItem('filtre');
        }

        if(isset($_GET['tri'])) {
            $ajoutQuerySiIndexLivre .= '&tri='.$_GET['tri'];
        }
        elseif ($this->session->getItem('tri')!=null) {
            $ajoutQuerySiIndexLivre .= '&tri='.$this->session->getItem('tri');
        }

        if(isset($_GET['page'])) {
            $ajoutQuerySiIndexLivre .= '&page='.$_GET['page'];
        }
        elseif(isset($_GET['idPage'])) {
            $ajoutQuerySiIndexLivre .= '&idPage='.$_GET['idPage'];
        }


        $refLivre = Livre::trouverUnLivreParISBN($isbn_livre);

        if ($this->session->getItem('panier')!==null) {
            $this->session->getItem('panier')->ajouterItem($refLivre, (int)$qteLivre, $formatLivre);
        }
        else {
            $this->sessionPanier->ajouterItem($refLivre, (int)$qteLivre, $formatLivre);
        }

        // Rediriger vers la page fiche du livre correspondant pour afficher le message dans la récap du panier
        if ($nomPage == 'Accueil') {
            header('Location: index.php?controleur=site&action=accueil&isbn='.$isbn_livre);
            exit;
        } else if($nomPage == "Livres") {
            header('Location: index.php?controleur=livre&action=index&isbn='.$isbn_livre.$ajoutQuerySiIndexLivre);
            exit;
        } else {
            header('Location: index.php?controleur=livre&action=fiche&idLivre='.$refLivre->id_livre.'&formatLivre='.$formatLivre);
            exit;
        }
    }

    /**
     * Permet d'afficher le panier
     */
    public function fiche():void {
//        if ($this->session->getItem('filAriane')!=null) {
//            $filAriane = $this->session->getItem('filAriane');
//        }
//        else {
            $filAriane = FilAriane::majFilAriane();
//        }


        // Aller voir l'état de connexion
        if ($this->session->getItem('estConnecte')){
            $etatConnexion = $this->session->getItem('estConnecte');
        }
        else {
            $etatConnexion = false;
        }

        $tDonnees = array('refSessionPanier' => $this->session->getItem('panier'), 'nomPage' => 'fichePanier', 'filAriane' => $filAriane, 'etatConnexion' => $etatConnexion);
        echo $this->blade->run('panier.fiche', $tDonnees);
    }

    /**
     * Permet de supprimer un item
     */
    public function supprimerItem():void {

        if(isset($_GET['isbn'])) {
            $isbn_livre = (string)$this->utilitaires->validerGetPost($_GET['isbn'], '#^([0-9]{1,5}-)([0-9]{1,7}-)([0-9]{1,6}-)([0-9]{1,3}|[Xx]{1})$#');
        }

        if (isset($_GET['pagePrecedente'])) {
            $pagePrecedente = (string)$this->utilitaires->validerGetPost($_GET['pagePrecedente'], '#^(Accueil|Livres|Fiche|Livraison)$#');
        }
        else {
            $pagePrecedente = 'Accueil';
        }

        if(isset($_GET['idLivre'])) {
            $idLivre = $_GET['idLivre'];
        }
        else {
            $idLivre=0;
        }
        $stringQuery = "&idLivre=".$idLivre;

        $this->session->getItem('panier')->supprimerItem((string)$isbn_livre);

        // Changer le mode de livraison si le nombre d'items égal 0 (donc que le panier est vide)
        // --- remet temporairement l'attribut à une valeur 'neutre'
        if ($this->session->getItem('panier')->getNombreTotalItemsDifferents()==0) {
            $this->session->getItem('panier')->changerModeLivraison('standard');
        }

        // Rediriger vers la page fiche du panier
        header('Location: index.php?controleur=panier&action=fiche&pagePrecedente='.$pagePrecedente.$stringQuery);
        exit;
    }

    /**
     * Permet de mettre à jour la quantité
     */
    public function majQuantite():void {
        if (isset($_GET['sansJs'])) {
            if (isset($_POST['choixQte'])) {
                $qteLivre = (int)$this->utilitaires->validerGetPost($_POST['choixQte'], '#^([1-9]|10)$#');
            }
            else {
                $qteLivre = 1;
            }

            if (isset($_GET['isbn'])) {
                $isbn_livre = (string)$this->utilitaires->validerGetPost($_GET['isbn'], '#^([0-9]{1,5}-)([0-9]{1,7}-)([0-9]{1,6}-)([0-9]{1,3}|[Xx]{1})$#');
            }
            else {
                $isbn_livre="";
            }

            if (isset($_GET['pagePrecedente'])) {
                $pagePrecedente = (string)$this->utilitaires->validerGetPost($_GET['pagePrecedente'], '#^(Accueil|Livres|Fiche|Livraison)$#');
            }
            else {
                $pagePrecedente = 'Accueil';
            }

            $this->session->getItem('panier')->setQuantiteItem((string)$isbn_livre, (int)$qteLivre);

            // Changer le mode de livraison si le montant après le changement de quantité tombe en bas de 50.00$
            if ($this->session->getItem('panier')->getMontantSousTotal() < 50) {
                $this->session->getItem('panier')->changerModeLivraison('standard');
            }

            // Rediriger vers la page fiche du panier
            header('Location: index.php?controleur=panier&action=fiche&pagePrecedente='.$pagePrecedente);
            exit;
        }
        else {
            if (isset($_GET['choixQte'])) {
                $qteLivre = $this->utilitaires->validerGetPost($_GET['choixQte'], '#^([1-9]|10)$#');
            }
            else {
                $qteLivre = 1;
            }

            if (isset($_GET['isbn'])) {
                $isbn_livre = (string)$this->utilitaires->validerGetPost($_GET['isbn'], '#^([0-9]{1,5}-)([0-9]{1,7}-)([0-9]{1,6}-)([0-9]{1,3}|[Xx]{1})$#');
            }
            else {
                $isbn_livre="";
            }

            $this->session->getItem('panier')->setQuantiteItem((string)$isbn_livre, (int)$qteLivre);

            // Changer le mode de livraison si le montant après le changement de quantité tombe en bas de 50.00$
            if ($this->session->getItem('panier')->getMontantSousTotal() < 50) {
                $this->session->getItem('panier')->changerModeLivraison('standard');
            }
            else {
                $this->session->getItem('panier')->changerModeLivraison('gratuit');
            }

            $prix_livreQte = $this->session->getItem('panier')->formaterPrix($this->session->getItem('panier')->getMontantTotalDunLivre($isbn_livre));
            $sousTotalTransaction = $this->session->getItem('panier')->formaterPrix($this->session->getItem('panier')->getMontantSousTotal());
            $taxesTransaction = $this->session->getItem('panier')->formaterPrix($this->session->getItem('panier')->getMontantTPS());
            $totalTransaction = $this->session->getItem('panier')->formaterPrix($this->session->getItem('panier')->getMontantTotal());
            $montantLivraison = $this->session->getItem('panier')->formaterPrix($this->session->getItem('panier')->getMontantFraisLivraison());
            $typeLivraison = $this->session->getItem('panier')->getModeLivraison();
            $quantiteTotaleItems = $this->session->getItem('panier')->getNombreTotalItems();

            $arrMontantsMAJ = array("prix_livreQte"=>$prix_livreQte, "sousTotalTransaction"=>$sousTotalTransaction, "taxesTransaction"=>$taxesTransaction, "totalTransaction"=>$totalTransaction, "montantLivraison"=>$montantLivraison, "typeLivraison"=>$typeLivraison, "qteTotaleItems"=>$quantiteTotaleItems);

           echo json_encode($arrMontantsMAJ);
        }
    }

    /**
     * Permet de mettre à jour le mode de livraison
     */
    public function majModeLivraison():void {
        if(isset($_GET['sansJs'])) {
            if(isset($_POST['choixLivraison'])) {
                $nouveauModeLivraison = (string)$this->utilitaires->validerGetPost($_POST['choixLivraison'], '#^(standard|prioritaire|gratuit)$#');
            }
            else {
                $nouveauModeLivraison = 'standard';
            }

            if (isset($_GET['pagePrecedente'])) {
                $pagePrecedente = (string)$this->utilitaires->validerGetPost($_GET['pagePrecedente'], '#^(Accueil|Livres|Fiche|Livraison)$#');
            }
            else {
                $pagePrecedente = 'Accueil';
            }

            $this->sessionPanier->changerModeLivraison($nouveauModeLivraison);

            // Rediriger vers la page fiche du panier
            header('Location: index.php?controleur=panier&action=fiche&pagePrecedente='.$pagePrecedente);
            exit;
        }
        else {
            if(isset($_GET['choixLivraison'])) {
                $nouveauModeLivraison = (string)$this->utilitaires->validerGetPost($_GET['choixLivraison'], '#^(standard|prioritaire|gratuit)$#');
            }
            else {
                $nouveauModeLivraison = 'standard';
            }

            $this->sessionPanier->changerModeLivraison($nouveauModeLivraison);

            $totalTransaction = $this->session->getItem('panier')->formaterPrix($this->session->getItem('panier')->getMontantTotal());
            $montantLivraison = $this->session->getItem('panier')->formaterPrix($this->session->getItem('panier')->getMontantFraisLivraison());
            $typeLivraison = $this->session->getItem('panier')->getModeLivraison();

            $arrMontantsMAJ = array("totalTransaction"=>$totalTransaction, "montantLivraison"=>$montantLivraison, "typeLivraison"=>$typeLivraison);

            echo json_encode($arrMontantsMAJ);
        }
    }
}