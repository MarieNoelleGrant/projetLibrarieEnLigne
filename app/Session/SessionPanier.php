<?php
declare(strict_types=1);

namespace App\Session;

use App\Modeles\Livre;
use App\App;
use App\Modeles\ModeLivraison;

class SessionPanier
{

    private $items = [];
    private $messageConfirmationPanier = array();
    private $refModeLivraison = 'standard';

    public function __construct()
    {
        $this->session = App::getInstance()->getSession();
    }


    // Ajoute un item au panier avec la quantité X
    // Attention: Si l'item existe déjà dans le panier alors mettre à jour la quantité (la quantité maximum est de 10 à valider...)
    /**
     * Ajoute un item au panier
     */
    public function ajouterItem(Livre $unLivre, int $uneQte, string $unFormatChoisi):void
    {
        $refISBN = $unLivre->isbn_livre;
        if(isset($this->items[$refISBN])==false) {
            $this->items[$refISBN] = new SessionItem($unLivre, $uneQte);
        }
        else {
            $qteActuelle = $this->items[$refISBN]->quantite;
            $qteAdditionnee = (int) $qteActuelle + (int) $uneQte;

            if($qteAdditionnee<=10) {
                $this->items[$refISBN]->quantite = $qteAdditionnee;
            }
            else {
                $this->items[$refISBN]->quantite = 10;
            }
       }

        $this->items[$refISBN]->formatChoisi = $unFormatChoisi;

        $this->messageConfirmationPanier = array('titre_livre'=>$this->items[$refISBN]->livre->titre_livre,
                                                 'prix_livre'=>$this->items[$refISBN]->livre->getPrixFormate($this->items[$refISBN]->livre->prix_livre),
                                                 'prix_electronique_livre'=>$this->items[$refISBN]->livre->getPrixFormate($this->items[$refISBN]->livre->prix_electronique_livre),
                                                 'quantite_livre'=>$uneQte);
        $this->sauvegarder();
    }

    // Supprimer un item du panier
    /**
     * Supprime un item du panier
     */
    public function supprimerItem(string $isbn):void
    {
        array_splice($this->items,array_search($isbn, array_keys($this->items)), 1);
    }

    // Retourner le tableau d'items du panier
    /**
     * Récupère le tableau d'items
     * @return {array} $this->items
     */
    public function getItems():array
    {
        return $this->items;
    }

    // Retourner un item du panier
    /**
     * Récupère un item
     * @param $isbn
     * @return {array} $this->items[$isbn]
     */
    public function getUnItem($isbn):SessionItem
    {
        return $this->items[$isbn];
    }

    // Mettre à jour la quantité d'un item
    /**
     * Actualise la quantité d'items d'un livre
     * @param {string} $isbn
     * @param {int} $uneQte
     */
    public function setQuantiteItem(string $isbn, int $uneQte):void
    {
        $this->items[$isbn]->quantite = $uneQte;
    }

    // Retourner la quantité d'un item
    /**
     * Récupère la quantité d'un item
     * @param {string} $isbn
     * @return {int} $total
     */
    public function getQuantiteItem(string $isbn):int
    {
        $total = $this->items[$isbn]->quantite;
        return $total;
    }


    // Retourner le nombre d'item différents (unique) dans le panier
    /**
     * Récupère le nombre de livres différents dans le panier
     * @return {int} $nb
     */
    public function getNombreTotalItemsDifferents():int
    {
        //$arrUnique = array_unique($this->items);
        $nb = count($this->items);
        return $nb;
    }

    public function getMontantTotalDunLivre(string $refISBN):float {
        return $this->items[$refISBN]->getMontantTotal();
    }

    // Retourner le nombre de livres total dans le panier (somme de la quantité de chaque item)
    /**
     * Récupère le nombre total de livres
     * @return {int} $nbTotalItems
     */
    public function getNombreTotalItems():int
    {
        $nbTotalItems = 0;
        foreach ($this->items as $ligne) {
            $nbTotalItems += $ligne->quantite;
        }
        return $nbTotalItems;
    }



    // Retourner le montant sousTotal du panier (somme des montantTotals de chaque item)
    /**
     * Récupère le montant sous-total
     * @return {float} $montantSousTotal
     */
    public function getMontantSousTotal():float{
        $montantSousTotal = 0;
        foreach ($this->items as $ligne) {
            $montantSousTotal += $ligne->getMontantTotal();
        }
        return $montantSousTotal;
    }


    // Retourner de montant de la TPS
    // TPS = 5%
    /**
     * Récupère le montant de la TPS
     * @return {float} $montantTPS
     */
    public function getMontantTPS():float {
        $montantTPS = $this->getMontantSousTotal();
        $montantTPS = $montantTPS * 0.05;
        return $montantTPS;
    }

    // Retourner le montant des frais de livraison
    // Frais de livraison (base=4$ + taux par item=3,50$) Exemple, 1livre=7,50$, 2livres=11$ etc.
    // Il n’y a pas de taxes sur les frais de livraison. Ils s’ajoutent en dernier.
    /**
     * Récupère le prix de la livraison
     * @return {float} $montantFrais
     */
    public function getMontantFraisLivraison():float
    {
        $nbTotalItems = $this->getNombreTotalItems();
        $montantFrais = null;
        if ($this->getMontantSousTotal()>50 && $this->refModeLivraison != 'prioritaire') {
            $this->refModeLivraison = 'gratuit';
            $montantFrais = 0;
        }
        else {
            if ($this->refModeLivraison=='gratuit') {
                $infosModeLivraison = ModeLivraison::trouverParNomModeLivraison('standard');
            }
            else {
                $infosModeLivraison = ModeLivraison::trouverParNomModeLivraison($this->refModeLivraison);
            }
            $montantFrais = $infosModeLivraison->base + ($nbTotalItems*$infosModeLivraison->par_item);
        }

        return $montantFrais;
    }

    // Retourner le montant total de la commande (montant sous-total + TPS + montant livraison)
    /**
     * Calcule le montant total
     * @return {float} $montantTotal
     */
    public function getMontantTotal():float
    {
        $montantSousTotal = $this->getMontantSousTotal();
        $montantTPS = $this->getMontantTPS();
        $montantLivraison = $this->getMontantFraisLivraison();
        $montantTotal = $montantSousTotal + $montantLivraison + $montantTPS;

        return $montantTotal;
    }

    // Retourner le message de confirmation du panier
    /**
     * Récupère le message de confirmation
     * @return {string} $this->messageConfirmationPanier
     */
    public function getMessage():array
    {
        return $this->messageConfirmationPanier;
    }

    // Vider le message de confirmation du panier
    /**
     * Vide le message de confirmation
     */
    public function viderMessage():void
    {
        $this->messageConfirmationPanier = array();
    }

    // Changer la valeur de la variable du mode de livraison
    /**
     * Récupérer le mode de livraison
     * @return {string} $this->refModeLivraison
     */
    public function getModeLivraison():string {
        return $this->refModeLivraison;
    }

    // Changer la valeur de la variable du mode de livraison
    /**
     * Changer le mode de livraison
     * @param {string} $nouveauModeLivraison
     */
    public function changerModeLivraison(string $nouveauModeLivraison):void {
        $this->refModeLivraison = $nouveauModeLivraison;
    }

    // Envoyer le message pour le délai de livraison
    /**
     * Récupérer le message du mode de livraison
     * @param {string} $modeLivraison
     * @return {string} $refMessageDelai
     */
    public function getMessageModeLivraison(string $modeLivraison):string {
        if ($modeLivraison!='gratuit') {
            $refMessageDelai = ModeLivraison::retournerMessageDelai($modeLivraison);
        }
        else {
            $refMessageDelai = ModeLivraison::retournerMessageDelai('standard');
        }
        return $refMessageDelai;
    }

    // Sauvegarder le panier en variable session nommée: panier
    /**
     * Sauvegarder le panier dans une session
     */
    public function sauvegarder():void
    {
        $this->session->setItem('panier', $this);
    }

    // Supprimer le panier en variable session nommée: panier
    /**
     * Supprimer le panier
     */
    public function supprimer(){
        $this->session->supprimerItem('panier');
    }

    /* FONCTIONS UTILITAIRES */
    /**
     * Formater le prix pour le panier
     * @param {float} $unPrix
     * @return {string} $prixFormate
     */
    public function formaterPrix(float $unPrix):string {
        $prixFormate = number_format((float)$unPrix, 2, ',', '') . " $";
        return $prixFormate;
    }
}
