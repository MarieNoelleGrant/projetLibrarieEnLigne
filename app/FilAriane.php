<?php
declare(strict_types=1);

namespace App;

use App\Modeles\Livre;
use App\Modeles\Categorie;

class FilAriane
{

    private static $session = null;

    public function __construct()
    {
    }

    /**
     * Crée le fil d'ariane
     * @return {array} $fil
     */
    public static function majFilAriane(){
        $fil=array();
        FilAriane::$session = App::getInstance()->getSession();

        //Si le contrôleur est défini
        if(isset($_GET["controleur"])){

            // CODE POUR LE FIL D'ARIANE DANS LA PAGE FICHE - Sert de retour vers la page précédente, avec les critères et les pages et tout le tralala
            if (($_GET['controleur']=='panier' && $_GET['action']=='fiche') ||
                ($_GET['controleur']=='client' && $_GET['action']=='ficheConnexion') ||
                    ($_GET['controleur']=='client' && $_GET['action']=='ficheCreer') ||
                    ($_GET['controleur']=='client' && $_GET['action']=='connexion') ||
                    ($_GET['controleur']=='client' && $_GET['action']=='creer')) {
                switch($_GET['pagePrecedente']) {
                    case 'Accueil': $fil = 'index.php?controleur=site&action=accueil';
                        break;
                    case 'Connexion': $fil = 'index.php?controleur=client&action=ficheConnexion';
                        break;
                    case 'Creer': $fil = 'index.php?controleur=client&action=ficheCreer';
                        break;
                    case 'Fiche': $fil = "index.php?controleur=livre&action=fiche&idLivre=".$_GET["idLivre"];
                        break;
                    case 'Livres': if(isset($_GET["nouveau"])){
                        $fil = "index.php?controleur=livre&action=index&nouveau=".$_GET["nouveau"];
                    }elseif (isset($_GET["fitre"])){
                        if(isset($_GET["page"])) {
                            $fil = "index.php?controleur=livre&action=index&filtre=" . $_GET["filtre"] . "&page=" . $_GET["page"];
                        }
                        else {
                            $fil = "index.php?controleur=livre&action=index&filtre=".$_GET["filtre"]."&page=0";
                        }
                    }elseif (FilAriane::$session->getItem('filtre')!=null) {
                        if(FilAriane::$session->getItem('page')!=null) {
                            $fil = "index.php?controleur=livre&action=index&filtre=".FilAriane::$session->getItem('filtre')."&page=".FilAriane::$session->getItem('page');
                        }
                        else{
                            $fil = "index.php?controleur=livre&action=index&filtre=".FilAriane::$session->getItem('filtre')."&page=0";
                        }
                    }elseif(isset($_GET["page"])) {
                        $fil = "index.php?controleur=livre&action=index"."&page=".$_GET["page"];
                    }
                    else {
                        $fil = "index.php?controleur=livre&action=index";
                    }
                    break;
                }
                FilAriane::$session->setItem('filAriane', $fil);
            }
            //Si le contrôleur n'est pas celui du site, nous sommes au deuxième niveau
            elseif($_GET["controleur"] !== 'site') {
                switch(true){
                    //Si l'action est d'afficher une liste de livres
                    case  $_GET["action"] === 'index' :

                        //Lien de retour vers l'accueil
                        $lien0=array("titre"=>"Accueil","lien"=>"index.php?controleur=site&action=accueil");

                        //@todo adapter cet algo pour les catéries...

                        //Titre de la page
                        if(isset($_GET["special"])){
                            if ($_GET["special"]=='nouveautes') {
                                $lien1=array("titre"=>"Nouveautés");
                            }
                            elseif ($_GET["special"]=='coups-coeur') {
                                $lien1=array("titre"=>"Coups de coeur");
                            }
                        }
                        else if(isset($_GET["fitre"])){
                            $lien1=array("titre"=>Categorie::trouverParId($_GET["fitre"])->nom_fr_categorie);
                        }
                        else if(FilAriane::$session->getItem('filtre')!=null) {
                            $lien1=array("titre"=>Categorie::trouverParId(FilAriane::$session->getItem('filtre'))->nom_fr_categorie);
                        }
                        else {
                            $lien1=array("titre"=>"Tous les livres");
                        }

                        $fil[0] = $lien0;
                        $fil[1] = $lien1;
                    break;

                    //Si l'action d'afficher une fiche de livre
                    case  $_GET["action"] === 'fiche' :

                        if (isset($_GET['pagePrecedente']) && $_GET['pagePrecedente']=='Accueil') {
                            $lien0=array("titre"=>"Accueil","lien"=>"index.php?controleur=site&action=accueil");
                            if(isset($_GET["idLivre"])) {
                                $livre = Livre::trouverUnLivre($_GET["idLivre"]);
                                $lien1=array("titre"=>$livre->titre_livre);
                            }
                            $fil[0] = $lien0;
                            $fil[1] = $lien1;
                        }
                        else {
                            //Lien de retour vers l'accueil
                            $lien0=array("titre"=>"Accueil","lien"=>"index.php?controleur=site&action=accueil");

                            //Lien vers la liste des pages se qualifiant (catégorie, nouveauté...)

                            //@todo adapter cet algo pour les catéries...
                            //Titre de la page
                            if(isset($_GET["fitre"])){
                                if(isset($_GET["page"])) {
                                    $lien1=array("titre"=>Categorie::trouverParId($_GET["fitre"])->nom_fr_categorie, "lien"=>"index.php?controleur=livre&action=index&filtre=".$_GET["filtre"]."&page=".$_GET["page"]);
                                }
                                else {
                                    $lien1=array("titre"=>Categorie::trouverParId($_GET["fitre"])->nom_fr_categorie, "lien"=>"index.php?controleur=livre&action=index&filtre=".$_GET["filtre"]."&page=0");
                                }
                            }
                            else {
                                if(FilAriane::$session->getItem('filtre')!=null) {
                                    if(FilAriane::$session->getItem('page')!=null) {
                                        $lien1=array("titre"=>Categorie::trouverParId(FilAriane::$session->getItem('filtre'))->nom_fr_categorie, "lien"=>"index.php?controleur=livre&action=index&filtre=".FilAriane::$session->getItem('filtre')."&page=".FilAriane::$session->getItem('page'));
                                    }
                                    else {
                                        $lien1=array("titre"=>Categorie::trouverParId(FilAriane::$session->getItem('filtre'))->nom_fr_categorie, "lien"=>"index.php?controleur=livre&action=index&filtre=".FilAriane::$session->getItem('filtre')."&page=0");
                                    }
                                }
                                else {
                                    if(isset($_GET["page"])) {
                                        $lien1=array("titre"=>"Tous les livres", "lien"=>"index.php?controleur=livre&action=index"."&page=".$_GET["page"]);
                                    }
                                    else {
                                        if(FilAriane::$session->getItem('special')!=null) {
                                            echo FilAriane::$session->getItem('special');
                                            if (FilAriane::$session->getItem('special')=='coups-coeur') {
                                                $lien1=array("titre"=>"Coups de coeur", "lien"=>"index.php?controleur=livre&action=index"."&special=coups-coeur");
                                            }
                                            else {
                                                if (FilAriane::$session->getItem('special')=='nouveautes') {
                                                    $lien1=array("titre"=>"Nouveautés", "lien"=>"index.php?controleur=livre&action=index"."&special=nouveautes");
                                                }
                                            }
                                        }
                                        else {
                                            $lien1=array("titre"=>"Tous les livres", "lien"=>"index.php?controleur=livre&action=index");
                                        }
                                    }
                                }
                            }
                            $fil[0] = $lien0;
                            $fil[1] = $lien1;
                            $fil[2] = array("titre"=>"Titre du livre");
                        }
                        break;
                }
            }
        }
        return $fil;
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