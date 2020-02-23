<?php

declare(strict_types=1);

namespace App\Modeles;

use App\App;
use \PDO;


class Auteur {
    // Attributs - champs de la table Autheur
    private $id_auteur;
    private $nom_auteur;
    private $prenom_auteur;
    private $biographie_auteur;
    private $url_blogue_auteur;


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

    // SELECT - Avoir les autheurs du livre (id) demandé
    /**
     * Trouver les auteurs d'un livre selon son ID
     * @param {string} $unIdLivre l'ID du livre
     * @return {array} $arrAuteurs
     */
    public static function trouverParId(string $unIdLivre):array {

        // on va chercher une instance de la connexion qui se trouve dans la class App
        $pdo = App::getInstance()->getPDO();

        // Définir la chaine SQL
        $chaineSQL = "SELECT * 
                      FROM auteurs INNER JOIN auteurs_livres ON auteurs.id_auteur = auteurs_livres.auteur_id
                      WHERE auteurs_livres.livre_id = :unId";
        // Préparer la requête (optimisation)
        $requetePreparee = $pdo->prepare($chaineSQL);
        // Définir la méthode de validation des variables associées aux marqueurs nommés (peut être un ?)de la requête
        $requetePreparee->bindParam(':unId', $unIdLivre, PDO::PARAM_INT);
        // Définir le mode de récupération
        // Comment je veux le résultat -> un tableau d'objet de la classe livre
        $requetePreparee->setFetchMode(PDO::FETCH_CLASS, Auteur::class);
        // Exécuter la requête
        $requetePreparee->execute();
        // Récupérer les occurences
        $arrAuteurs = $requetePreparee->fetchAll();

        return $arrAuteurs;
    }

    /**
     * Rechercher un auteur par valeur textuelle dans le champ de recherche
     * @param {string} $valeur
     * @return {array} $arrAuteurs
     */
    public static function chercherAuteur(string $valeur):array {
        $pdo = App::getInstance()->getPDO();
        $chaine1 = "%" . $valeur . "%";
        $chaine2 = "%" . $valeur . "%";
//        $chaineSQL = "SELECT * FROM auteurs WHERE nom_auteur LIKE '" . $chaine1 . "' OR prenom_auteur LIKE '" . $chaine2 . "' ORDER BY prenom_auteur, nom_auteur LIMIT 5";
        $chaineSQL = "SELECT * FROM auteurs WHERE CONCAT(nom_auteur,' ', prenom_auteur) LIKE '" . $chaine1 . "' OR CONCAT(prenom_auteur, ' ', nom_auteur) LIKE '" . $chaine2 . "' ORDER BY prenom_auteur, nom_auteur LIMIT 5";
        $requetePreparee = $pdo->prepare($chaineSQL);
        $requetePreparee->setFetchMode(PDO::FETCH_CLASS, Auteur::class);
        $requetePreparee->execute();
        $arrAuteurs = $requetePreparee->fetchAll();
        return $arrAuteurs;
    }

    // Champ calculé pour avoir le prénom ET nom
    /**
     * Assemble le prénom et le nom de l'auteur
     * @return {string} $prenomNom
     */
    public function getPrenomNom():string {
        $prenomNom = $this->prenom_auteur . " " . $this->nom_auteur;
        return $prenomNom;
    }
}