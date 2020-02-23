<?php

declare(strict_types = 1);
namespace App;

use \PDO;

class ConnexionBD
{
    private $serveur = '';
    private $utilisateur = '';
    private $motDePasse = '';
    private $nomBd = '';
    private $chaineDsn = '';

    private $pdo = null;


    public function __construct(string $unServeur, string $unUtilisateur, string $unMotDePasse, string $unNomBd)
    {
        $this->serveur = $unServeur;
        $this->utilisateur = $unUtilisateur;
        $this->motDePasse = $unMotDePasse;
        $this->nomBd = $unNomBd;
        $this->chaineDsn = 'mysql:dbname=' . $this->nomBd . ';host=' . $this->serveur; //Data Source Name pour l'objet PDO
    }

    /**
     * Crée la connexion PDO
     * @return {PDO} $this->pdo
     */
    public function getNouvelleConnexionPDO(): PDO
    {
        //Tentative de connexion
        $this->pdo = new PDO($this->chaineDsn, $this->utilisateur, $this->motDePasse);

        // Changement d'encodage des caractères UTF-8
        $this->pdo->exec("SET CHARACTER SET utf8");

        // Affectation des attributs de la connexion : Obtenir des rapports d'erreurs et d'exception avec errorInfo()
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        return $this->pdo;
    }
}