<?php

declare(strict_types=1);

namespace App\Modeles;

use App\App;
use \PDO;


class Livre {
    // les champs de la table livres
    private $id_livre;
    private $titre_livre;
    private $prix_livre;
    private $prix_electronique_livre;
    private $nbre_pages_livre;
    private $est_illustre_livre;
    private $annee_publication_livre;
    private $langue_livre;
    private $sous_titre_livre;
    private $mots_cles_livre;
    private $isbn_livre;
    private $description_livre;
    private $autres_caracteristiques_livre;
    private $est_coup_de_coeur_livre;
    private $id_parution;
    private $id_collection;

    public function __construct()
    {
    }

    // MAGIE !!!
    public function __get($property)
    {
        if (property_exists($this, $property)) {
            return $this->$property;
        }
    }

    public function __set($property, $value)
    {
        if (property_exists($this, $property)) {
            $this->$property = $value;
        }
    }

    // SELECT - Avoir tous les livres
    //------------------------------------------------//
    /**
     * Récupère tous les livres
     * @return {array} $arrLivresTout
     */
    public static function trouverTout():array {

        // on va chercher une instance de la connexion qui se trouve dans la class App
        $pdo = App::getInstance()->getPDO();

        // Définir la chaine SQL
        $chaineSQL = 'SELECT * 
                      FROM livres';
        // Préparer la requête (optimisation)
        $requetePreparee = $pdo->prepare($chaineSQL);
        // Définir le mode de récupération
        // Comment je veux le résultat -> un tableau d'objet de la classe livre
        $requetePreparee->setFetchMode(PDO::FETCH_CLASS, Livre::class);
        // Exécuter la requête
        $requetePreparee->execute();
        // Récupérer les occurences
        $arrLivresTout = $requetePreparee->fetchAll();

        return $arrLivresTout;
    }

    /**
     * Récupère un livre selon son ISBN
     * @param {string} $unISBN
     * @return {Livre} $refUnLivre
     */
    public static function trouverUnLivreParISBN(string $unISBN):Livre {

        // on va chercher une instance de la connexion qui se trouve dans la class App
        $pdo = App::getInstance()->getPDO();

        // Définir la chaine SQL
        $chaineSQL = 'SELECT * 
                      FROM livres
                      WHERE isbn_livre = :unISBN';
        // Préparer la requête (optimisation)
        $requetePreparee = $pdo->prepare($chaineSQL);
        // Binding des paramètres
        $requetePreparee->bindParam(':unISBN', $unISBN, PDO::PARAM_STR);
        // Définir le mode de récupération
        // Comment je veux le résultat -> un tableau d'objet de la classe livre
        $requetePreparee->setFetchMode(PDO::FETCH_CLASS, Livre::class);
        // Exécuter la requête
        $requetePreparee->execute();
        // Récupérer les occurences
        $refUnLivre = $requetePreparee->fetch();

        return $refUnLivre;
    }


    // SELECT - Avoir toutes les infos pour un livre
    //------------------------------------------------//
    /**
     * Récupère un livre selon son ID
     * @param {string} $unIdLivre
     * @return {Livre} $refUnLivre
     */
    public static function trouverUnLivre(string $unIdLivre):Livre {

        // on va chercher une instance de la connexion qui se trouve dans la class App
        $pdo = App::getInstance()->getPDO();

        // Définir la chaine SQL
        $chaineSQL = 'SELECT * 
                      FROM livres
                      WHERE id_livre = :unIdLivre';
        // Préparer la requête (optimisation)
        $requetePreparee = $pdo->prepare($chaineSQL);
        // Binding des paramètres
        $requetePreparee->bindParam(':unIdLivre', $unIdLivre, PDO::PARAM_INT);
        // Définir le mode de récupération
        // Comment je veux le résultat -> un tableau d'objet de la classe livre
        $requetePreparee->setFetchMode(PDO::FETCH_CLASS, Livre::class);
        // Exécuter la requête
        $requetePreparee->execute();
        // Récupérer les occurences
        $refUnLivre = $requetePreparee->fetch();

        return $refUnLivre;
    }

  // SELECT - Avoir tous les coups de coeur
  //------------------------------------------------//
    /**
     * Récupérer tous les coups de coeur
     * @param {int} $refLimit Nombre de livres désirés
     * @return {array} $arrLivresCoeur
     */
  public static function trouverCoupCoeur(int $refLimit):array {

    // on va chercher une instance de la connexion qui se trouve dans la class App
    $pdo = App::getInstance()->getPDO();

    // Définir la chaine SQL
    $chaineSQL = 'SELECT * 
                      FROM livres
                      WHERE est_coup_de_coeur_livre = 1
                      ORDER BY annee_publication_livre DESC 
                      LIMIT :uneLimit';
    // Préparer la requête (optimisation)
    $requetePreparee = $pdo->prepare($chaineSQL);
    // Définir la méthode de validation des variables associées aux marqueurs(argument reçu) nommés de la requête
    $requetePreparee->bindParam(':uneLimit', $refLimit, PDO::PARAM_INT);
    // Définir le mode de récupération
    // Comment je veux le résultat -> un tableau d'objet de la classe livre
    $requetePreparee->setFetchMode(PDO::FETCH_CLASS, Livre::class);
    // Exécuter la requête
    $requetePreparee->execute();
    // Récupérer les occurences
    $arrLivresCoeur = $requetePreparee->fetchAll();

    return $arrLivresCoeur;
  }

    // COUNT - compte combien il y a livre au complet
    //------------------------------------------------//
    /**
     * Compter tous les livres
     * @return {int} (int)$nblivre["total"]
     */
    public static function compter():int {

        // on va chercher une instance de la connexion qui se trouve dans la class App
        $pdo = App::getInstance()->getPDO();

        // Définir la chaine SQL
        $chaineSQL = 'SELECT COUNT(id_livre) as total  
                      FROM livres';
        // Préparer la requête (optimisation)
        $requetePreparee = $pdo->prepare($chaineSQL);
        // Définir le mode de récupération
        // Comment je veux le résultat -> un tableau d'objet de la classe livre
        //$requetePreparee->setFetchMode(PDO::FETCH_CLASS, Livre::class);
        // Exécuter la requête
        $requetePreparee->execute();
        // Récupérer les occurences
        $nblivre = $requetePreparee->fetch();

        return (int)$nblivre["total"];
    }

    /**
     * Compter les livres d'une catégorie spécifique
     * @param {string} $id_livre
     * @return {int} (int)$nblivre["total"]
     */
    public static function compterTri(string $id_livre):int {
        $pdo = App::getInstance()->getPDO();
        $chaineSQL = 'SELECT COUNT(id_livre) as total  
                      FROM livres
                      INNER JOIN categories_livres 
                      ON livres.id_livre = categories_livres.livre_id 
                      WHERE categorie_id = :unId';
        $requete = $pdo->prepare($chaineSQL);
        $requete->bindParam(":unId", $id_livre, PDO::PARAM_INT);
        $requete->execute();
        $nbLivre =  $requete->fetch();
        return (int)$nbLivre["total"];
    }

    /**
     * Compter les coups de coeur
     * @return {int} (int)$nblivre["total"]
     */
    public static function compterCoupsCoeur():int {
        $pdo = App::getInstance()->getPDO();
        $chaineSQL = 'SELECT COUNT(id_livre) as total
                      FROM livres
                      WHERE est_coup_de_coeur_livre = 1';
        $requete = $pdo->prepare($chaineSQL);
        $requete->bindParam(":unId", $id_livre, PDO::PARAM_INT);
        $requete->execute();
        $nbLivre =  $requete->fetch();
        return (int)$nbLivre["total"];
    }

    /**
     * Compter les nouveautés
     * @return {int} (int)$nblivre["total"]
     */
    public static function compterNouveautes():int {
        $pdo = App::getInstance()->getPDO();
        $chaineSQL = 'SELECT COUNT(id_livre) as total
                      FROM livres
                      INNER JOIN parutions
                      ON livres.id_parution = parutions.id_parution
                      WHERE etat_parution = "Disponible"';
        $requete = $pdo->prepare($chaineSQL);
        $requete->bindParam(":unId", $id_livre, PDO::PARAM_INT);
        $requete->execute();
        $nbLivre =  $requete->fetch();
        return (int)$nbLivre["total"];
    }

    /**
     * Créer une description raccourcie
     * @return {string} $resultat
     */
    // Méthode de  champ calculer
    public function getMiniDesc():string{
        $resultat = "";
        $excerpt = explode(' ', $this->description_livre);
        $excerpt = array_slice($excerpt, 0, 100);
        $resultat .= implode(' ', $excerpt).' '."[...]";
        return $resultat;
    }

    // Parution associée
    /**
     * Trouver la parution associée à ce livre
     * @return {Parution} $parution
     */
    public function getParution():Parution {
        $parution = Parution::trouver($this->id_parution);
        return $parution;
    }

    // Collection associée
    /**
     * Trouver la collection associée à ce livre
     * @return {Collection} $collection
     */
    public function getCollection():Collection {
        $collection = Collection::trouver($this->id_parution);
        return $collection;
    }

    // Revoyer le prix formaté au besoin
    /**
     * Compter les livres d'une catégorie spécifique
     * @param {string} $refPrix
     * @return {string} $prixRetourne
     */
    public function getPrixFormate($refPrix):string {
        $prixARetourner = $refPrix;
        $prixRetourne = number_format((float)$prixARetourner, 2, ',', '') . ' $';
        return $prixRetourne;
    }

    // Auteurs du livre
    /**
     * Récupère les auteurs de ce livre
     * @return {array} $arrAuteurs
     */
    public function getAuteurs():array {
        $arrAuteurs = Auteur::trouverParId($this->id_livre);
        return $arrAuteurs;
    }

    // Catégories du livre
    /**
     * Récupère les catégories de ce livre
     * @return {array} $arrCategories
     */
    public function getCategories():array {
        $arrCategories = Categorie::trouverParLivre($this->id_livre);
        return $arrCategories;
    }

    // Éditeurs du livre
    /**
     * Récupère les éditeurs de ce livre
     * @return {array} $arrEditeurs
     */
    public function getEdition():array {
        $arrEditeurs = Editeur::trouverParLivre($this->id_livre);
        return $arrEditeurs;
    }

    // Honneurs (prix remportés) du livre
    /**
     * Récupère les honneurs de ce livre
     * @return {array} $arrHonneurs
     */
    public function getHonneur():array {
        $arrHonneurs = Honneur::trouverParLivre($this->id_livre);
        return $arrHonneurs;
    }

    // Mentions (recensions)
    /**
     * Récupère les mentions de ce livre
     * @return {array} $arrMentions
     */
    public function getMentions():array {
        $arrMentions = Mention::trouverParLivre($this->id_livre);
        return $arrMentions;
    }
    
    // Formater les titres pour les pages accueil et fiche
    /**
     * Formate le titre de ce livre pour avoir l'article au début
     * @return {string} $titreLivreFormate
     */
    public function getTitreFormate():string {
        $titreLivreOriginal = $this->titre_livre;
        $titreLivreFormate = "";
        
        if (strpos($titreLivreOriginal, "(L')")!=false) {
            $indexARemplacer = strpos($titreLivreOriginal, "(L')");
            $titreLivreOriginal = substr_replace($titreLivreOriginal, "", $indexARemplacer);
            $titreLivreFormate = "L'" . $titreLivreOriginal;
        }
        elseif (strpos($titreLivreOriginal, "(La)")!=false) {
            $indexARemplacer = strpos($titreLivreOriginal, "(La)");
            $titreLivreOriginal = substr_replace($titreLivreOriginal, "", $indexARemplacer);
            $titreLivreFormate = "La " . $titreLivreOriginal;
        }
        elseif (strpos($titreLivreOriginal, "(Le)")!=false) {
            $indexARemplacer = strpos($titreLivreOriginal, "(Le)");
            $titreLivreOriginal = substr_replace($titreLivreOriginal, "", $indexARemplacer);
            $titreLivreFormate = "Le " . $titreLivreOriginal;
        }
        elseif (strpos($titreLivreOriginal, "(Les)")!=false) {
            $indexARemplacer = strpos($titreLivreOriginal, "(Les)");
            $titreLivreOriginal = substr_replace($titreLivreOriginal, "", $indexARemplacer);
            $titreLivreFormate = "Les " . $titreLivreOriginal;
        }
        elseif (strpos($titreLivreOriginal, "(The)")!=false) {
            $indexARemplacer = strpos($titreLivreOriginal, "(The)");
            $titreLivreOriginal = substr_replace($titreLivreOriginal, "", $indexARemplacer);
            $titreLivreFormate = "The " . $titreLivreOriginal;
        }
        elseif (strpos($titreLivreOriginal, "[épuisé]")!=false) {
            $indexARemplacer = strpos($titreLivreOriginal, "[épuisé]");
            $titreLivreOriginal = substr_replace($titreLivreOriginal, "(épuisé)", $indexARemplacer);
            $titreLivreFormate = $titreLivreOriginal;
        }
        else {
            $titreLivreFormate = $titreLivreOriginal;
        }

        return $titreLivreFormate;
    }

    /**
     * Crée un titre raccourci pour le livre
     * @return {string} $titreRaccourci
     */
    public function getTitreRaccourci():string {
        $titreFormate = $this->getTitreFormate();
        $titreExplose = explode(' ', $titreFormate);
        if (count($titreExplose)>3) {
            $titreExploseRaccourci = array_splice($titreExplose, 0, 3, array());
            $titreRaccourci = implode(' ', $titreExploseRaccourci) . ' (...)';
        }
        else {
            $titreRaccourci = $titreFormate;
        }
        return $titreRaccourci;
    }

    // Placer la couverture du livre selon son ISBN ou un placeholder s'il n'y en a pas
    /**
     * Place la couverture selon l'ISBN ou selon le placeholder
     * @return {string} $nomfichier
     */
    public function getSrcAvecISBN():string {
        $strISBN10=$this->isbn_livre;

        //Conversion
        $strISBN13="L".$this->ISBNToEAN($strISBN10)."1";

        $nomfichier="liaisons/images/couvertures/".$strISBN13.".jpg";

        //Détection du fichier
        if (file_exists($nomfichier)===false) {
            $nomfichier="liaisons/images/placeholder.jpg";
        }
        return $nomfichier;
    }

    /**
     * Tri tous les livres
     * @param {string} $tri
     * @param {int} $debut
     * @param $fin
     * @return {array} $arrLivres
     */
    public static function trier(string $tri, int $debut, string $fin):array {
        $pdo = App::getInstance()->getPDO();
        switch($tri) {
            case "alpha":
                $chaineSQL = 'SELECT * 
                      FROM livres 
                      ORDER BY titre_livre
                      LIMIT :unDebut, :uneFin';
                break;
            case "alpha_desc":
                $chaineSQL = 'SELECT * 
                      FROM livres 
                      ORDER BY titre_livre DESC
                      LIMIT :unDebut, :uneFin';
                break;
            case "prix":
                $chaineSQL = 'SELECT * 
                      FROM livres 
                      ORDER BY prix_livre
                      LIMIT :unDebut, :uneFin';
                break;
            case "prix_desc":
                $chaineSQL = 'SELECT * 
                      FROM livres 
                      ORDER BY prix_livre DESC 
                      LIMIT :unDebut, :uneFin';
                break;
            default:
                $chaineSQL = 'SELECT * 
                      FROM livres 
                      ORDER BY titre_livre
                      LIMIT :unDebut, :uneFin';
        }
        $fin = (int) $fin;
        $requete = $pdo->prepare($chaineSQL);
        $requete->bindParam(':unDebut', $debut, PDO::PARAM_INT);
        $requete->bindParam(':uneFin', $fin, PDO::PARAM_INT);
        $requete->setFetchMode(PDO::FETCH_CLASS, Livre::class);
        $requete->execute();
        $arrLivres = $requete->fetchAll();
        return $arrLivres;
    }

    /**
     * Tri les livres quand une catégorie est activée
     * @param {string} $tri
     * @param {string} $idCategorie
     * @param {int} $debut
     * @param {string} $fin
     * @return {array} $arrLivres
     */
    public static function trierFiltrer(string $tri, string $idCategorie, int $debut, string $fin):array {
        $pdo = App::getInstance()->getPDO();
        switch($tri) {
            case "alpha":
                $chaineSQL = 'SELECT * 
                      FROM livres 
                      INNER JOIN categories_livres 
                      ON livres.id_livre = categories_livres.livre_id 
                      WHERE categorie_id = :unId 
                      ORDER BY titre_livre
                      LIMIT :unDebut, :uneFin';
                break;
            case "alpha_desc":
                $chaineSQL = 'SELECT * 
                      FROM livres 
                      INNER JOIN categories_livres 
                      ON livres.id_livre = categories_livres.livre_id 
                      WHERE categorie_id = :unId 
                      ORDER BY titre_livre DESC
                      LIMIT :unDebut, :uneFin';
                break;
            case "prix":
                $chaineSQL = 'SELECT * 
                      FROM livres 
                      INNER JOIN categories_livres 
                      ON livres.id_livre = categories_livres.livre_id 
                      WHERE categorie_id = :unId 
                      ORDER BY prix_livre
                      LIMIT :unDebut, :uneFin';
                break;
            case "prix_desc":
                $chaineSQL = 'SELECT * 
                      FROM livres 
                      INNER JOIN categories_livres 
                      ON livres.id_livre = categories_livres.livre_id 
                      WHERE categorie_id = :unId 
                      ORDER BY prix_livre DESC 
                      LIMIT :unDebut, :uneFin';
                break;
            default:
                $chaineSQL = 'SELECT * 
                      FROM livres 
                      INNER JOIN categories_livres 
                      ON livres.id_livre = categories_livres.livre_id 
                      WHERE categorie_id = :unId 
                      ORDER BY titre_livre
                      LIMIT :unDebut, :uneFin';
        }
        $fin = (int) $fin;
        $requete = $pdo->prepare($chaineSQL);
        $requete->bindParam(':unId', $idCategorie, PDO::PARAM_INT);
        $requete->bindParam(':unDebut', $debut, PDO::PARAM_INT);
        $requete->bindParam(':uneFin', $fin, PDO::PARAM_INT);
        $requete->setFetchMode(PDO::FETCH_CLASS, Livre::class);
        $requete->execute();
        $arrLivres = $requete->fetchAll();
        return $arrLivres;
    }

    /**
     * Tri les coups de coeur
     * @param {string} $tri
     * @param {int} $debut
     * @param $fin
     * @return {array} $arrLivres
     */
    public static function trierCoupsCoeur(string $tri, int $debut, string $fin):array {
        $pdo = App::getInstance()->getPDO();
        switch($tri) {
            case "alpha":
                $chaineSQL = 'SELECT * 
                              FROM livres 
                              WHERE est_coup_de_coeur_livre = 1
                              ORDER BY titre_livre
                              LIMIT :unDebut, :uneFin';
                break;
            case "alpha_desc":
                $chaineSQL = 'SELECT * 
                              FROM livres 
                              WHERE est_coup_de_coeur_livre = 1
                              ORDER BY titre_livre DESC
                              LIMIT :unDebut, :uneFin';
                break;
            case "prix":
                $chaineSQL = 'SELECT * 
                              FROM livres 
                              WHERE est_coup_de_coeur_livre = 1
                              ORDER BY prix_livre
                              LIMIT :unDebut, :uneFin';
                break;
            case "prix_desc":
                $chaineSQL = 'SELECT * 
                              FROM livres 
                              WHERE est_coup_de_coeur_livre = 1
                              ORDER BY prix_livre DESC 
                              LIMIT :unDebut, :uneFin';
                break;
            default:
                $chaineSQL = 'SELECT * 
                              FROM livres 
                              WHERE est_coup_de_coeur_livre = 1
                              ORDER BY titre_livre
                              LIMIT :unDebut, :uneFin';
        }
        $fin = (int) $fin;
        $requete = $pdo->prepare($chaineSQL);
        $requete->bindParam(':unDebut', $debut, PDO::PARAM_INT);
        $requete->bindParam(':uneFin', $fin, PDO::PARAM_INT);
        $requete->setFetchMode(PDO::FETCH_CLASS, Livre::class);
        $requete->execute();
        $arrLivres = $requete->fetchAll();
        return $arrLivres;
    }

    /**
     * Tri les nouveautés
     * @param {string} $tri
     * @param {int} $debut
     * @param $fin
     * @return {array} $arrLivres
     */
    public static function trierNouveautes(string $tri, int $debut, string $fin):array {
        $pdo = App::getInstance()->getPDO();
        switch($tri) {
            case "alpha":
                $chaineSQL = 'SELECT * 
                              FROM livres 
                              INNER JOIN parutions
                              ON livres.id_parution = parutions.id_parution
                              WHERE etat_parution = "Disponible"
                              ORDER BY titre_livre
                              LIMIT :unDebut, :uneFin';
                break;
            case "alpha_desc":
                $chaineSQL = 'SELECT * 
                              FROM livres 
                              INNER JOIN parutions
                              ON livres.id_parution = parutions.id_parution
                              WHERE etat_parution = "Disponible"
                              ORDER BY titre_livre DESC
                              LIMIT :unDebut, :uneFin';
                break;
            case "prix":
                $chaineSQL = 'SELECT * 
                              FROM livres 
                              INNER JOIN parutions
                              ON livres.id_parution = parutions.id_parution
                              WHERE etat_parution = "Disponible"
                              ORDER BY prix_livre
                              LIMIT :unDebut, :uneFin';
                break;
            case "prix_desc":
                $chaineSQL = 'SELECT * 
                              FROM livres 
                              INNER JOIN parutions
                              ON livres.id_parution = parutions.id_parution
                              WHERE etat_parution = "Disponible"
                              ORDER BY prix_livre DESC 
                              LIMIT :unDebut, :uneFin';
                break;
            default:
                $chaineSQL = 'SELECT * 
                              FROM livres 
                              WHERE est_coup_de_coeur_livre = 1
                              ORDER BY titre_livre
                              LIMIT :unDebut, :uneFin';
        }
        $fin = (int) $fin;
        $requete = $pdo->prepare($chaineSQL);
        $requete->bindParam(':unDebut', $debut, PDO::PARAM_INT);
        $requete->bindParam(':uneFin', $fin, PDO::PARAM_INT);
        $requete->setFetchMode(PDO::FETCH_CLASS, Livre::class);
        $requete->execute();
        $arrLivres = $requete->fetchAll();
        return $arrLivres;
    }

    public function ISBNToEAN ($strISBN) {
        $myFirstPart = $mySecondPart = $myEan = $myTotal = "";
        if ($strISBN == "")
           return "";
        $strISBN = str_replace("-", "", $strISBN);
        // ISBN-10
        if (strlen($strISBN) == 10)
        {
            $myEan = "978" . substr($strISBN, 0, 9);
            $myFirstPart = intval(substr($myEan, 1, 1)) + intval(substr($myEan, 3, 1)) + intval(substr($myEan, 5, 1)) + intval(substr($myEan, 7, 1)) + intval(substr($myEan, 9, 1)) + intval(substr($myEan, 11, 1));
            $mySecondPart = intval(substr($myEan, 0, 1)) + intval(substr($myEan, 2, 1)) + intval(substr($myEan, 4, 1)) + intval(substr($myEan, 6, 1)) + intval(substr($myEan, 8, 1)) + intval(substr($myEan, 10, 1));
            $tmp = intval(substr((string)(3 * $myFirstPart + $mySecondPart), -1));
            $myControl = ($tmp == 0) ? 0 : 10 - $tmp;

            return $myEan . $myControl;
        }
        // ISBN-13
        else if (strlen($strISBN) == 13) return $strISBN;
        // Autre
        else return "";
    }

    /**
     * Calcule le prix de la version électronique, et l'enregistre dans la B.D. s'il ne l'est pas déjà!
     * @return {float} (float)$this->prix_electronique_livre
     */
    public function getPrixVersionElectro():float {
        if ($this->prix_electronique_livre==0) {
            $arrDiffPrixElectro = array(1.50, 2.00, 2.50);
            $diffPrix = $arrDiffPrixElectro[mt_rand(0, 2)];
            $this->prix_electronique_livre = ($this->prix_livre - $diffPrix);
            $this->updatePrixElectro($this->prix_electronique_livre);
        }
        return (float)$this->prix_electronique_livre;
    }

    /**
     * Met à jour le prix electronique
     * @param $refPrixElectro non typé car sinon la BD l'arrondit
     */
    public function updatePrixElectro($refPrixElectro):void {
        $pdo = App::getInstance()->getPDO();
        $chaineSQL = 'UPDATE livres
                      SET prix_electronique_livre=:unPrixElectro
                      WHERE id_livre=:unId';
        $requete = $pdo->prepare($chaineSQL);
        $requete->bindParam(":unPrixElectro", $refPrixElectro);
        $requete->bindParam(":unId", $this->id_livre, PDO::PARAM_INT);
        $requete->execute();
    }
}