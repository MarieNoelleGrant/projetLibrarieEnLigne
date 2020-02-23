<?php

declare(strict_types=1);

namespace App\Modeles;

use App\App;
use PDO;

class ModeLivraison
{

    private $id_mode_livraison;
    private $date_mise_a_jour;
    private $mode_livraison;
    private $base;
    private $par_item;
    private $delai;
    private $delai_max_jrs;

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

    /**
     * Récupère un mode de livraison selon son nom
     * @param {string} $refNomModeLivraison
     * @return {ModeLivraison} $refModeLivraison
     */
    public static function trouverParNomModeLivraison($refNomModeLivraison):ModeLivraison {
        // on va chercher une instance de la connexion qui se trouve dans la class App
        $pdo = App::getInstance()->getPDO();

        // Définir la chaine SQL
        $chaineSQL = 'SELECT * 
                      FROM mode_livraison
                      WHERE mode_livraison = :nomMode';
        // Préparer la requête (optimisation)
        $requetePreparee = $pdo->prepare($chaineSQL);
        // Définir la méthode de validation des variables associées aux marqueurs(argument reçu) nommés de la requête
        $requetePreparee->bindParam(':nomMode', $refNomModeLivraison, PDO::PARAM_STR);
        // Définir le mode de récupération
        // Comment je veux le résultat -> un tableau d'objet de la classe livre
        $requetePreparee->setFetchMode(PDO::FETCH_CLASS, ModeLivraison::class);
        // Exécuter la requête
        $requetePreparee->execute();
        // Récupérer les occurences
        $refModeLivraison = $requetePreparee->fetch();

        return $refModeLivraison;
    }

    /**
     * Récupère le message de délai selon le nom du mode de livraison
     * @param {string} $refNomModeLivraison
     * @return {string} $refModeLivraison['delai']
     */
    public static function retournerMessageDelai(string $refNomModeLivraison):string {
        // on va chercher une instance de la connexion qui se trouve dans la class App
        $pdo = App::getInstance()->getPDO();

        // Définir la chaine SQL
        $chaineSQL = 'SELECT delai
                      FROM mode_livraison
                      WHERE mode_livraison = :nomMode';
        // Préparer la requête (optimisation)
        $requetePreparee = $pdo->prepare($chaineSQL);
        // Définir la méthode de validation des variables associées aux marqueurs(argument reçu) nommés de la requête
        $requetePreparee->bindParam(':nomMode', $refNomModeLivraison, PDO::PARAM_STR);
        // Exécuter la requête
        $requetePreparee->execute();
        // Récupérer les occurences
        $refModeLivraison = $requetePreparee->fetch();

        return $refModeLivraison['delai'];
    }

}