<?php
/**
 * Created by PhpStorm.
 * User: solorock
 * Date: 19-08-18
 * Time: 10:25
 */

namespace App;

class Cookie
{

    public function __construct()
    {
    }

    public function set(string $unNom, $uneValeur, int $dateExpirationEnSeconde):void
    {
        // $uneValeur: valeur unique(chaine, int, etc.) ou un tableau.

        $uneValeur = json_encode($uneValeur);
        // Demander au navigateur de créer un cookie
        setcookie($unNom, $uneValeur, $dateExpirationEnSeconde);
        // L'ajouter au tableau $_COOKIE pour que la valeur soit immédiatement disponible pour le reste du script PHP.
        $_COOKIE[$unNom] = $uneValeur;
    }

    public function get($unNom){
        $valeur = null;
        if(isset($_COOKIE[$unNom])) {
            $valeur = json_decode($_COOKIE[$unNom]);
        }
        return $valeur;
    }

    public function supprimer($unNom):void{
        // Demander au navigateur d'effacer le cookie (c'est à dire le faire expirer...)
        setcookie($unNom, '', 1);
        // Supprimer cookie immédiatement sur le serveur pour
        // éviter de l'utiliser par erreur dans la suite du script
        unset($_COOKIE[$unNom]);
    }

}