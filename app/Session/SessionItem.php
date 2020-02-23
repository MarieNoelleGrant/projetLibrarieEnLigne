<?php
declare(strict_types=1);

namespace App\Session;

use App\Modeles\Livre;

class SessionItem
{
    private $livre = null;
    private $quantite = 0;
    private $formatChoisi = null;

    public function __construct(Livre $unLivre, int $uneQte)
    {
        $this->livre = $unLivre;
        $this->quantite = $uneQte;
    }


    // Retourne le montant total d'un item (prix x quantité)
    /**
     * Calcule le montant total
     * @return {float} $total
     */
    public function getMontantTotal():float
    {
        if ($this->formatChoisi=='papier') {
            $total = $this->quantite * $this->livre->prix_livre;
        }
        else {
            $total = $this->quantite * $this->livre->prix_electronique_livre;
        }

        return $total;
    }

    // Getter / Setter (magique)
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
}