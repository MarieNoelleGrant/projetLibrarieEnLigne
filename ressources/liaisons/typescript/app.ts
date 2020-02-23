import {Validations} from './Validations';
import {MAJQuantite} from './MAJQuantite';
import {DispoCourriel} from "./DispoCourriel";
import {MAJModeLivraison} from "./MAJModeLivraison";
import {Autocompletion} from "./Autocompletion";
import {CoupsCoeurInfo} from "./CoupsCoeurInfo";

new Validations();

if($('main').hasClass('panier')) {
    new MAJQuantite();
    new MAJModeLivraison();
}
if($('main').hasClass('creer')) {
    this.refAjaxDispoCourriel = new DispoCourriel();
}
if($('header').hasClass('entete--complete')) {
    new Autocompletion();
}
if($('main').hasClass('accueil')) {
    new CoupsCoeurInfo();
}

