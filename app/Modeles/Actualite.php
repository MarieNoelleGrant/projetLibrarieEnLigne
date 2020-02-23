<?php

declare(strict_types=1);

namespace App\Modeles;

use App\App;
use \PDO;

class Actualite {

    // Attributs
    private $id_actualite;
    private $date_actualite;
    private $titre_actualite;
    private $texte_actualite;
    private $id_auteur;


    // MÉTHODES
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


    // SELECT - Avoir toutes les actualités
    //------------------------------------------------//
    /**
     * Récupérer toutes les actualités
     * @return {array} $arrActualitesTout
     */
    public static function trouverTout():array {

        // on va chercher une instance de la connexion qui se trouve dans la class App
        $pdo = App::getInstance()->getPDO();

        // Définir la chaine SQL
        $chaineSQL = 'SELECT * 
                      FROM actualites';
        // Préparer la requête (optimisation)
        $requetePreparee = $pdo->prepare($chaineSQL);
        // Définir le mode de récupération
        // Comment je veux le résultat -> un tableau d'objet de la classe livre
        $requetePreparee->setFetchMode(PDO::FETCH_CLASS, Actualite::class);
        // Exécuter la requête
        $requetePreparee->execute();
        // Récupérer les occurences
        $arrActualitesTout = $requetePreparee->fetchAll();

        return $arrActualitesTout;
    }

    // SELECT - Avoir une selection d'actualités
    //------------------------------------------------//
    /**
     * Avoir un nombre délimité d'actualités
     * @param {int} $debut
     * @param {int} $fin
     * @return {array} $arrActualitesLimit
     */
    public static function trouverLimit(int $debut, int $fin):array {

        // on va chercher une instance de la connexion qui se trouve dans la class App
        $pdo = App::getInstance()->getPDO();

        // Définir la chaine SQL
        $chaineSQL = 'SELECT * 
                      FROM actualites
                      LIMIT :unDebut, :uneFin';
        // Préparer la requête (optimisation)
        $requetePreparee = $pdo->prepare($chaineSQL);
        // Définir la méthode de validation des variables associées aux marqueurs(argument reçu) nommés de la requête
        $requetePreparee->bindParam(':unDebut', $debut, PDO::PARAM_INT);
        $requetePreparee->bindParam(':uneFin', $fin, PDO::PARAM_INT);
        // Définir le mode de récupération
        // Comment je veux le résultat -> un tableau d'objet de la classe livre
        $requetePreparee->setFetchMode(PDO::FETCH_CLASS, Actualite::class);
        // Exécuter la requête
        $requetePreparee->execute();
        // Récupérer les occurences
        $arrActualitesLimit = $requetePreparee->fetchAll();

        return $arrActualitesLimit;
    }


    // Auteur de l'actualité
    //------------------------------------------------//
    /**
     * Récupérer les auteurs d'un livre
     * @return {array} $arrAuteur
     */
    public function getAuteurs():array {
        $arrAuteur = Auteur::trouverParId($this->id_auteur);
        return $arrAuteur;
    }


    // Méthode de champ calculer pour le texte
    //------------------------------------------------//
    /**
     * Créer un texte raccourci
     * @return {string} $resultat
     */
    public function getMiniTexte():string{
        $resultat = null;
        $excerpt = explode(' ', $this->texte_actualite);
        $excerpt = array_slice($excerpt, 0, 50);
        $resultat .= implode(' ', $excerpt).' '."[...]";
        return $resultat;
    }
}