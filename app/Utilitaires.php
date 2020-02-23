<?php
declare(strict_types=1);

namespace App;

class Utilitaires {


    private $tMessagesJSON = null;

    public function validerFormulaire($nomChamp, $valChamp, $regex):array {
        $message = '';
        $estValide = false;

        // Récupérer le contenu des messages en format JSON
        $contenuBruteFichierJson = file_get_contents("liaisons/js/objMessages.json");
        $this->tMessagesJSON = json_decode($contenuBruteFichierJson, true); // Convertir en tableau associatif

        if ($valChamp == '') {
            $message = $this->tMessagesJSON[$nomChamp]['vide'];
        } else {
            $motifValide = preg_match($regex, $valChamp);
            if ($motifValide == false) {
                $message = $this->tMessagesJSON[$nomChamp]['motif'];
            }
            else {
                $estValide = true;
            }
        }

        $arrValidation = ["valeur"=>$valChamp, "message"=>$message, "estValide"=>$estValide];
        return $arrValidation;
    }

    /**
     * Valide la valeur reçue selon une RegExp reçue
     * @param $uneValeur
     * @param $uneRegex
     * @return $laValeurValidee
     */
    public function validerGetPost($uneValeur, $uneRegex) {
        $laValeurValidee = null;
        if (preg_match($uneRegex, $uneValeur)) {
            $laValeurValidee = $uneValeur;
        }
        else {
            $laValeurValidee = -1;
        }
        return $laValeurValidee;
    }
}

?>