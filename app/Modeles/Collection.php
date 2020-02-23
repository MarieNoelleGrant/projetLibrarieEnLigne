<?php

declare(strict_types=1);

namespace App\Modeles;

use App\App;
use \PDO;


class Collection {
    // ATTIBUTS  - champs de la table Parution
    private $id_collection;
    private $nom_collection;
    private $description_collection;


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
     * Récupérer la collection selon son ID
     * @param {string} $unIdCollection
     * @return {Collection} $collection
     */
    public static function trouver(string $unIdCollection):Collection {

        $pdo = App::getInstance()->getPDO();

        $chaineSQL = "SELECT nom_collection, description_collection 
                      FROM collections
                      WHERE collections.id_collection = :unId";

        $requetePreparee = $pdo->prepare($chaineSQL);
        $requetePreparee->bindParam(':unId', $unIdCollection, PDO::PARAM_INT);
        $requetePreparee->setFetchMode(PDO::FETCH_CLASS, Collection::class);
        $requetePreparee->execute();
        $collection = $requetePreparee->fetch();

        return $collection;
    }
}