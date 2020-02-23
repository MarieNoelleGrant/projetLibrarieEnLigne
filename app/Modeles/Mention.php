<?php

declare(strict_types=1);

namespace App\Modeles;

use App\App;
use \PDO;
use \DateTime;


class Mention {
    // ATTIBUTS  - champs de la table Parution
    private $id_recension;
    private $date_recension;
    private $titre_recension;
    private $nom_media_recension;
    private $nom_journaliste_recension;
    private $description_recension;
    private $id_livre;


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
     * Récupérer les mentions d'un livre selon son ID
     * @param {string} $unIdLivre
     * @return {array} $arrMentions
     */
    public static function trouverParLivre(string $unIdLivre):array {

        // on va chercher une instance de la connexion qui se trouve dans la class App
        $pdo = App::getInstance()->getPDO();

        // Définir la chaine SQL
        $chaineSQL = "SELECT * 
                      FROM recensions
                      WHERE recensions.id_livre = :unId";
        // Préparer la requête (optimisation)
        $requetePreparee = $pdo->prepare($chaineSQL);
        // Définir la méthode de validation des variables associées aux marqueurs(argument reçu) nommés de la requête
        $requetePreparee->bindParam(':unId', $unIdLivre, PDO::PARAM_INT);
        // Définir le mode de récupération
        // Comment je veux le résultat -> un tableau d'objet de la classe livre // à cause de la POO
        $requetePreparee->setFetchMode(PDO::FETCH_CLASS, Mention::class);
        // Exécuter la requête
        $requetePreparee->execute();
        // Récupérer les occurences
        $arrMentions = $requetePreparee->fetchAll();

        return $arrMentions;
    }

    /**
     * Place la date dans un format utilisable
     * @throws {string} $strDateFormatee
     */
    public function formaterDate():string {
        setlocale(LC_TIME, 'fr_CA');
        $dateBrute = new DateTime($this->date_recension);
        $refJour = $dateBrute->format('d');
        $refMois = $dateBrute->format('m');
        $refAnnee = $dateBrute->format('Y');
        $strDateFormatee = strftime('%A %d %B %Y', mktime(0,0,0,(int)$refMois,(int)$refJour,(int)$refAnnee));
        return $strDateFormatee;
    }
}