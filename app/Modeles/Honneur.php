<?php

declare(strict_types=1);

namespace App\Modeles;

use App\App;
use \PDO;

class Honneur {

    //Attributs
    private $id_honneur;
    private $nom_honneur;
    private $description_honneur;

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

    /**
     * Récupère les honneurs d'un livre selon son ID
     * @param {string} $unIdLivre
     * @return {array} $arrHonneurs
     */
    public static function trouverParLivre(string $unIdLivre):array {
        $pdo = App::getInstance()->getPDO();

        $chaineSQL = "SELECT nom_honneur, description_honneur 
                      FROM honneurs INNER JOIN honneurs_livres ON honneurs.id_honneur = honneurs_livres.honneur_id
                      WHERE honneurs_livres.livre_id = :unId";

        $requetePreparee = $pdo->prepare($chaineSQL);
        $requetePreparee->bindParam(':unId', $unIdLivre, PDO::PARAM_INT);
        $requetePreparee->setFetchMode(PDO::FETCH_CLASS, Honneur::class);
        $requetePreparee->execute();

        $arrHonneurs = $requetePreparee->fetchAll();
        return $arrHonneurs;
    }
}