<?php

//declare(strict_types=1);

namespace App\Modeles;

use App\App;
use \PDO;

class Categorie {

    //Attributs
    private $id_categorie;
    private $nom_fr_categorie;
    private $nom_en_categorie;


    //Méthodes

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

    // SELECT - Avoir toutes les categories (static)
    /**
     * Récupérer toutes les catégories
     * @return {array} $arrCategories
     */
    public static function afficherCategories():array {

        // on va chercher une instance de la connexion qui se trouve dans la class App
        $pdo = App::getInstance()->getPDO();

        // Définir la chaine SQL
        $chaineSQL = 'SELECT * 
                      FROM categories';
        // Préparer la requête (optimisation)
        $requetePreparee = $pdo->prepare($chaineSQL);
        // Définir le mode de récupération
        // Comment je veux le résultat -> un tableau d'objet de la classe livre
        $requetePreparee->setFetchMode(PDO::FETCH_CLASS, Categorie::class);
        // Exécuter la requête
        $requetePreparee->execute();
        // Récupérer les occurences
        $arrCategories = $requetePreparee->fetchAll();
        return $arrCategories;
    }

    /**
     * Récupérer la catégorie selon le livre
     * @param {int} $unIdLivre
     * @return {array} $arrCategories
     */
    public static function trouverParLivre(string $unIdLivre):array {
        $pdo = App::getInstance()->getPDO();

        $chaineSQL = "SELECT nom_fr_categorie, nom_en_categorie 
                      FROM categories INNER JOIN categories_livres ON categories.id_categorie = categories_livres.categorie_id
                      WHERE categories_livres.livre_id = :unId";

        $requetePreparee = $pdo->prepare($chaineSQL);
        $requetePreparee->bindParam(':unId', $unIdLivre, PDO::PARAM_INT);
        $requetePreparee->setFetchMode(PDO::FETCH_CLASS, Categorie::class);
        $requetePreparee->execute();

        $arrCategories = $requetePreparee->fetchAll();

        return $arrCategories;
    }

    /**
     * Récupérer la catégorie selon le ID de la catégorie
     * @param {string} $unIdCategorie
     * @return {Categorie} $arrCategories
     */
    public static function trouverParId(string $unIdCategorie):Categorie {
        $pdo = App::getInstance()->getPDO();

        $chaineSQL = "SELECT * 
                      FROM categories
                      WHERE id_categorie = :unId";

        $requetePreparee = $pdo->prepare($chaineSQL);
        $requetePreparee->bindParam(':unId', $unIdCategorie, PDO::PARAM_INT);
        $requetePreparee->setFetchMode(PDO::FETCH_CLASS, Categorie::class);
        $requetePreparee->execute();
        $arrCategories = $requetePreparee->fetch();
        return $arrCategories;
    }
}