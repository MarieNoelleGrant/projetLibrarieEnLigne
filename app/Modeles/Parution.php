<?php

declare(strict_types=1);

namespace App\Modeles;

use App\App;
use \PDO;


class Parution {
    // ATTIBUTS  - champs de la table Parution
    private $id_parution;
    private $etat_parution;


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

    // SELECT - Avoir la parution selon un id de livre
    /**
     * Récupère une parution selon son ID
     * @param {string} $unIdParution
     * @return {Parution} $parution
     */
    public static function trouver(string $unIdParution):Parution {

        // on va chercher une instance de la connexion qui se trouve dans la class App
        $pdo = App::getInstance()->getPDO();

        // Définir la chaine SQL
        $chaineSQL = "SELECT etat_parution 
                      FROM parutions
                      WHERE parutions.id_parution = :unId";
        // Préparer la requête (optimisation)
        $requetePreparee = $pdo->prepare($chaineSQL);
        // Définir la méthode de validation des variables associées aux marqueurs(argument reçu) nommés de la requête
        $requetePreparee->bindParam(':unId', $unIdParution, PDO::PARAM_INT);
        // Définir le mode de récupération
        // Comment je veux le résultat -> un tableau d'objet de la classe livre // à cause de la POO
        $requetePreparee->setFetchMode(PDO::FETCH_CLASS, Parution::class);
        // Exécuter la requête
        $requetePreparee->execute();
        // Récupérer les occurences
        $parution = $requetePreparee->fetch();

        return $parution;
    }
}