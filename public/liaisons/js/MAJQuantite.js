define(["require", "exports"], function (require, exports) {
    "use strict";
    Object.defineProperty(exports, "__esModule", { value: true });
    var MAJQuantite = /** @class */ (function () {
        function MAJQuantite() {
            this.executerAjax_lier = null;
            this.executerAjax_lier = this.executerAjax.bind(this);
            this.initialiser();
        }
        MAJQuantite.retournerResultat = function (data, textStatus, jqXHR, refISBN) {
            var selectId = '#' + refISBN;
            var refParentPrix = $('.panier__resumeListe');
            var refRecapPrix = $('.panier__recap');
            $(selectId).closest('.itemPanier__quantite').siblings('.itemPanier__total').find('.montantAjax').text(JSON.parse(data)['prix_livreQte']);
            $(refParentPrix).find('.panier__sousTotal').find('.montantAjax').text(JSON.parse(data)['sousTotalTransaction']);
            $(refParentPrix).find('.panier__TPS').find('.montantAjax').text(JSON.parse(data)['taxesTransaction']);
            $(refParentPrix).find('.panier__livraison').find('.montantAjax').text(JSON.parse(data)['montantLivraison']);
            $(refParentPrix).find('.panier__totalTotal').find('.montantAjax').text(JSON.parse(data)['totalTransaction']);
            var chaineOptionSelectLivraison = '[value="' + JSON.parse(data)['typeLivraison'] + '"]';
            $(refParentPrix).find('#choixLivraison').find('option').removeAttr('selected');
            if (JSON.parse(data)['typeLivraison'] == 'gratuit') {
                $(refParentPrix).find('#choixLivraison').find(chaineOptionSelectLivraison).removeClass('displayNone');
                $(refParentPrix).find('#choixLivraison').find('[value="standard"]').addClass('displayNone');
            }
            else {
                $(refParentPrix).find('#choixLivraison').find('[value="gratuit"]').addClass('displayNone');
                $(refParentPrix).find('#choixLivraison').find('[value="standard"]').removeClass('displayNone');
            }
            $(refParentPrix).find('#choixLivraison').find(chaineOptionSelectLivraison).attr('selected', 'selected');
            $(refRecapPrix).find('.panier__recapSection_qte').find('span').text(JSON.parse(data)['qteTotaleItems'] + " items");
            $(refRecapPrix).find('.panier__recapSection_total').find('span').text(JSON.parse(data)['totalTransaction'] + " (avec taxes)");
        };
        MAJQuantite.prototype.executerAjax = function (evenement) {
            var select = $(evenement.currentTarget);
            $.ajax({
                url: 'index.php?controleur=panier&action=majQuantite&isbn=' + select.attr('id'),
                type: 'GET',
                data: 'choixQte=' + select.val()
            })
                .done(function (data, textStatus, jqXHR) {
                MAJQuantite.retournerResultat(data, textStatus, jqXHR, select.attr('id'));
            });
        };
        MAJQuantite.prototype.initialiser = function () {
            var arrSelectQte = document.querySelectorAll('.selectQte');
            if (arrSelectQte.length > 0) {
                for (var intCpt = 0; intCpt < arrSelectQte.length; intCpt++) {
                    arrSelectQte[intCpt].addEventListener('change', this.executerAjax_lier);
                }
            }
        };
        return MAJQuantite;
    }());
    exports.MAJQuantite = MAJQuantite;
});
//# sourceMappingURL=MAJQuantite.js.map