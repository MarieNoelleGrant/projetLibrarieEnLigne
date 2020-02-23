<?php

declare(strict_types=1);

namespace App\Modeles;

use App\App;
use \PDO;


class Commande
{
    private $id_commande;
    private $etat;
    private $date;
    private $telephone;
    private $courriel;
    private $adresse_client_id;
    private $id_taux;
    private $id_mode_livraison;
    private $id_mode_payement;
    private $id_adresse_livraison;

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
     * Insère une commande dans la BD
     * @param {string} $etat
     * @param {string} $date
     * @param {string} $telephone
     * @param {string} $courriel
     * @param {string} $adresse
     * @param {string} $idTaux
     * @param {string} $idModeLiv
     * @param {string} $idModePayement
     * @param {string} $idAdresseLiv
     */
    public static function insererCommande(string $etat, string $date, string $telephone, string $courriel, string $adresse, string $idTaux, string $idModeLiv, string $idModePayement, string $idAdresseLiv):void {
        $pdo = App::getInstance()->getPDO();
        $chaineSQL = "INSERT INTO commande (etat, commande.date, telephone, courriel, adresse_client_id, id_taux, id_mode_livraison, id_mode_paiement, id_adresse_livraison) 
                      VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $requetePreparee = $pdo->prepare($chaineSQL);
        $requetePreparee->bindValue(1, $etat);
        $requetePreparee->bindValue(2, $date);
        $requetePreparee->bindValue(3, $telephone);
        $requetePreparee->bindValue(4, $courriel);
        $requetePreparee->bindValue(5, $adresse);
        $requetePreparee->bindValue(6, $idTaux);
        $requetePreparee->bindValue(7, $idModeLiv);
        $requetePreparee->bindValue(8, $idModePayement);
        $requetePreparee->bindValue(9, $idAdresseLiv);
        $requetePreparee->execute();
    }
}