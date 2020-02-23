<?php

declare(strict_types=1);

namespace App\Modeles;

use App\App;
use \PDO;


class Adresse
{
    private $id_adresse;
    private $prenom;
    private $nom;
    private $adresse;
    private $ville;
    private $code_postal;
    private $est_defaut;
    private $type;
    private $abbr_province;

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
     * Insère l'adresse dans la base de données
     * @param {string} $refPrenom
     * @param {string} $refNom
     * @param {string} $refAdresse
     * @param {string} $refVille
     * @param {string} $refPostal
     * @param {int} $refEstDefaut
     * @param {string} $refType
     * @param {string} $refProvince
     * @return {string} $dernierID
     */
    public static function insererAdresse(string $refPrenom, string $refNom, string $refAdresse, string $refVille, string $refPostal, int $refEstDefaut, string $refType, string $refProvince):string {
        $pdo = App::getInstance()->getPDO();
        $chaineSQL = "INSERT INTO adresse (prenom, nom, adresse, ville, code_postal, est_defaut, adresse.type, abbr_province) 
                      VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $requetePreparee = $pdo->prepare($chaineSQL);
        $requetePreparee->bindValue(1, $refPrenom);
        $requetePreparee->bindValue(2, $refNom);
        $requetePreparee->bindValue(3, $refAdresse);
        $requetePreparee->bindValue(4, $refVille);
        $requetePreparee->bindValue(5, $refPostal);
        $requetePreparee->bindValue(6, $refEstDefaut);
        $requetePreparee->bindValue(7, $refType);
        $requetePreparee->bindValue(8, $refProvince);
        $requetePreparee->execute();

        $dernierID = $pdo->lastInsertId();
        return $dernierID;
    }
}
