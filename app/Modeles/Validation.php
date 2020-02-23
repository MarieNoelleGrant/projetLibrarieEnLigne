<?php

declare(strict_types=1);

namespace App\Modeles;

use App\App;
use \PDO;


class Validation
{
    // À ADAPTER !!!! 
    private $id;
    private $prenom;
    private $nom;
    private $adresse;
    private $ville;
    private $pays;
    private $code_postal;
    private $courriel;
    private $tel_indicatif;
    private $tel_numero;
    private $date_jour;
    private $date_mois;
    private $date_annee;
    private $reponse_participant;
    private $acceptation_reglements;
    private $acceptation_documentation;


    public function __construct(){

    }



    // Getter / Setter (magique)

    public function __get($property) {
        if (property_exists($this, $property)) {
            return $this->$property;
        }
    }

    public function __set($property, $value) {
        if (property_exists($this, $property)) {
            $this->$property = $value;
        }
    }
}
