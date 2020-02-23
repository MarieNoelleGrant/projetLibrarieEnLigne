<?php

declare(strict_types=1);

namespace App\Modeles;

use App\App;
use \PDO;

class Editeur {

    //Attributs
    private $id_editeur;
    private $nom_editeur;
    private $url_editeur;


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
     * Récupérer les éditeurs du livre selon son ID
     * @param {string} $unIdLivre
     * @return {array} $arrEditeurs
     */
    public static function trouverParLivre(string $unIdLivre):array {
        $pdo = App::getInstance()->getPDO();

        $chaineSQL = "SELECT * 
                      FROM editeurs INNER JOIN editeurs_livres ON editeurs.id_editeur = editeurs_livres.editeur_id
                      WHERE editeurs_livres.livre_id = :unId";

        $requetePreparee = $pdo->prepare($chaineSQL);
        $requetePreparee->bindParam(':unId', $unIdLivre, PDO::PARAM_INT);
        $requetePreparee->setFetchMode(PDO::FETCH_CLASS, Editeur::class);
        $requetePreparee->execute();

        $arrEditeurs = $requetePreparee->fetchAll();

        return $arrEditeurs;
    }
}