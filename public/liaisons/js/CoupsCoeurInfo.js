define(["require", "exports"], function (require, exports) {
    "use strict";
    Object.defineProperty(exports, "__esModule", { value: true });
    var CoupsCoeurInfo = /** @class */ (function () {
        // CONSTRUCTEUR
        function CoupsCoeurInfo() {
            // ATTRIBUTS
            this.executerAjax_lier = null;
            this.executerAjax_lier = this.executerAjax.bind(this);
            this.initialiser();
        }
        // MÉTHODES
        CoupsCoeurInfo.prototype.initialiser = function () {
            var arrCoupsCoeur = Array.apply(null, document.querySelectorAll('.coupCoeur__grid_item'));
            for (var i = 0; i <= arrCoupsCoeur.length - 1; i++) {
                arrCoupsCoeur[i].addEventListener('focus', this.executerAjax_lier);
            }
        };
        // Une fonction de retour (CALLBACK) qui va être appelé une fois qu'ajax va avoir fini
        CoupsCoeurInfo.retournerResultat = function (data, textStatus, jqXHR) {
            console.log(data);
            var arrInfoCoupCoeur = JSON.parse(data);
            var livre = $('#' + arrInfoCoupCoeur["id"]);
            // livre.addClass('coupCoeur--selected');
            console.log(livre);
            $('.coupCoeur__grid_item--desc').addClass('coupCoeur__info').html('' +
                '<p class="h4 coupCoeur__info_titre">' + arrInfoCoupCoeur["titre"] + '</p>' +
                '<p class="coupCoeur__info_auteurs">' + arrInfoCoupCoeur["auteurs"] + '</p>' +
                '<p class="coupCoeur__info_prix">' + arrInfoCoupCoeur["prix"] + '</p>' +
                '<p class="coupCoeur__info_categories">' + arrInfoCoupCoeur["categories"] + '</p>' +
                '<a href="index.php?controleur=livre&action=fiche&idLivre=' + arrInfoCoupCoeur["id"] + '&pagePrecedente=Accueil' + '" class="bouton bouton--principal">Voir la fiche</a>');
        };
        // AJAX !
        CoupsCoeurInfo.prototype.executerAjax = function (evenement) {
            $('.coupCoeur__grid_item').removeClass('coupCoeur__grid_item--selected');
            var livre = $(evenement.currentTarget);
            livre.addClass('coupCoeur__grid_item--selected');
            // console.log('je passe dans ajax');
            $.ajax({
                url: 'index.php?controleur=site&action=infoCoupCoeur',
                type: 'GET',
                data: 'dataID=' + livre.attr('id'),
                dataType: 'html'
            })
                .done(function (data, textStatus, jqXHR) {
                CoupsCoeurInfo.retournerResultat(data, textStatus, jqXHR); // envoie au callback
            });
        };
        return CoupsCoeurInfo;
    }());
    exports.CoupsCoeurInfo = CoupsCoeurInfo;
});
//# sourceMappingURL=CoupsCoeurInfo.js.map