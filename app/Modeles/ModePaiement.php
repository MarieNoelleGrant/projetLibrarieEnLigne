<?php

declare(strict_types=1);

namespace App\Modeles;

use App\App;
use PDO;

class ModePaiement {

    private $id_mode_paiement;
    private $est_paypal;
    private $nom_complet;
    private $no_carte;
    private $type_carte;
    private $date_expiration_carte;
    private $code;
    private $est_defaut;
    private $id_client;

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
     * Insère un mode de paiement dans la BD
     * @param {string} $refEstPaypal
     * @param {string} $refnom
     * @param {int} $refNoCarte
     * @param {string} $refTypeCarte Peut être null si paypal
     * @param {string} $refDate
     * @param $refCodeSec
     * @param {string} $refEstDefaut
     * @param {string} $refIdClient
     * @return {string} $dernierID
     */
    public static function insererModePaiement(string $refEstPaypal, string $refNom, int $refNoCarte, ?string $refTypeCarte, string $refDate, $refCodeSec, string $refEstDefaut, string $refIdClient):string {
        $pdo = App::getInstance()->getPDO();
        $chaineSQL = "INSERT INTO mode_paiement (est_paypal, nom_complet, no_carte, type_carte, date_expiration_carte, code, est_defaut, id_client) 
                      VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $requetePreparee = $pdo->prepare($chaineSQL);
        $requetePreparee->bindValue(1, $refEstPaypal);
        $requetePreparee->bindValue(2, $refNom);
        $requetePreparee->bindValue(3, $refNoCarte);
        $requetePreparee->bindValue(4, $refTypeCarte);
        $requetePreparee->bindValue(5, $refDate);
        $requetePreparee->bindValue(6, $refCodeSec);
        $requetePreparee->bindValue(7, $refEstDefaut);
        $requetePreparee->bindValue(8, $refIdClient);
        $requetePreparee->execute();
        $dernierID = $pdo->lastInsertId();
        return $dernierID;
    }
}