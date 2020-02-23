<?php

declare(strict_types=1);

namespace App\Modeles;

use App\App;
use \PDO;


class Client
{
    private $id;
    private $prenom;
    private $nom;
    private $courriel;
    private $telephone;
    private $mot_de_passe;
    private $id_adresse;

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
     * Trouver un client par son courriel
     * ** N'est pas typé, puisqu'il peut retourner un booleen (selon ce qui est présent dans la base de données)
     * @param {string} $refCourriel
     * @return {Client} $refUnClient
     */
    public static function trouverParCourriel(?string $refCourriel) {
        // on va chercher une instance de la connexion qui se trouve dans la class App
        $pdo = App::getInstance()->getPDO();

        // Définir la chaine SQL
        $chaineSQL = 'SELECT * 
                      FROM client
                      WHERE courriel = :refCourriel';
        // Préparer la requête (optimisation)
        $requetePreparee = $pdo->prepare($chaineSQL);
        // Binding des paramètres
        $requetePreparee->bindParam(':refCourriel', $refCourriel, PDO::PARAM_STR);
        // Définir le mode de récupération
        // Comment je veux le résultat -> un tableau d'objet de la classe livre
        $requetePreparee->setFetchMode(PDO::FETCH_CLASS, Client::class);
        // Exécuter la requête
        $requetePreparee->execute();
        // Récupérer les occurences
        $refUnClient = $requetePreparee->fetch();

        return $refUnClient;
    }

    /**
     * Insère un nouveau client dans la BD
     * @param {string} $refPrenom
     * @param {string} $refnom
     * @param {string} $refCourriel
     * @param {string} $refTel
     * @param {string} $refMDP
     */
    public static function inserer(?string $refPrenom, ?string $refnom, ?string $refCourriel, ?string $refTel, ?string $refMDP):void
    {
        // on va chercher une instance de la connexion qui se trouve dans la class App
        $pdo = App::getInstance()->getPDO();

        $chaineSQL = 'INSERT INTO client (prenom, nom, courriel, telephone, mot_de_passe, id_adresse) 
                           VALUES (?, ?, ?, ?, ?, ?)';

        // 3. Exécution de la requête Update
        $requetePreparee = $pdo->prepare($chaineSQL);

        $requetePreparee->bindValue(1, $refPrenom);
        $requetePreparee->bindValue(2, $refnom);
        $requetePreparee->bindValue(3, $refCourriel);
        $requetePreparee->bindValue(4, $refTel);
        $requetePreparee->bindValue(5, $refMDP);
        $requetePreparee->bindValue(6, 1);

        $requetePreparee->execute();
    }

    /**
     * Change l'adrese associée à un client
     * @param {string} $idAdresse
     * @param {string} $idClient
     */
    public static function changerAdresseClient(string $idAdresse, string $idClient):void {
        $pdo = App::getInstance()->getPDO();
        $chaineSQL = 'UPDATE client SET id_adresse = :id_adresse
                      WHERE id = :id_client';
        $requetePreparee = $pdo->prepare($chaineSQL);
        $requetePreparee->bindParam("id_adresse", $idAdresse);
        $requetePreparee->bindParam("id_client", $idClient);
        $requetePreparee->execute();
    }


    // Champ calculé pour avoir le prénom ET nom
    /**
     * Assemble le prénom et le nom du client
     * @return {string} $prenomNom
     */
    public function getPrenomNom():string {
        $prenomNom = $this->prenom . " " . $this->nom;
        return $prenomNom;
    }
}

