define(["require", "exports", "./Validations", "./MAJQuantite", "./DispoCourriel", "./MAJModeLivraison", "./Autocompletion", "./CoupsCoeurInfo"], function (require, exports, Validations_1, MAJQuantite_1, DispoCourriel_1, MAJModeLivraison_1, Autocompletion_1, CoupsCoeurInfo_1) {
    "use strict";
    Object.defineProperty(exports, "__esModule", { value: true });
    new Validations_1.Validations();
    if ($('main').hasClass('panier')) {
        new MAJQuantite_1.MAJQuantite();
        new MAJModeLivraison_1.MAJModeLivraison();
    }
    if ($('main').hasClass('creer')) {
        this.refAjaxDispoCourriel = new DispoCourriel_1.DispoCourriel();
    }
    if ($('header').hasClass('entete--complete')) {
        new Autocompletion_1.Autocompletion();
    }
    if ($('main').hasClass('accueil')) {
        new CoupsCoeurInfo_1.CoupsCoeurInfo();
    }
});
//# sourceMappingURL=app.js.map