define(["require", "exports"], function (require, exports) {
    "use strict";
    Object.defineProperty(exports, "__esModule", { value: true });
    var MAJModeLivraison = /** @class */ (function () {
        function MAJModeLivraison() {
            this.executerAjax_lier = null;
            this.executerAjax_lier = this.executerAjax.bind(this);
            this.initialiser();
        }
        MAJModeLivraison.retournerResultat = function (data, textStatus, jqXHR) {
            var refSelect = $('#choixLivraison');
            var refParent = refSelect.closest('.panier__livraison');
            var refRecapPrix = $('.panier__recap');
            $(refParent).find('.montantAjax').text(JSON.parse(data)['montantLivraison']);
            $(refParent).siblings('.panier__totalTotal').find('.montantAjax').text(JSON.parse(data)['totalTransaction']);
            var chaineOptionSelectLivraison = '[value="' + JSON.parse(data)['typeLivraison'] + '"]';
            refSelect.find('option').removeAttr('selected');
            if (JSON.parse(data)['typeLivraison'] == 'gratuit') {
                refSelect.find(chaineOptionSelectLivraison).removeClass('displayNone');
            }
            else {
                if (refSelect.find(chaineOptionSelectLivraison).hasClass('displayNone') == false) {
                    refSelect.find(chaineOptionSelectLivraison).removeClass('displayNone');
                }
            }
            //refSelect.find(chaineOptionSelectLivraison).attr('selected', 'selected');
            $(refRecapPrix).find('.panier__recapSection_total').find('span').text(JSON.parse(data)['totalTransaction'] + " (avec taxes)");
        };
        MAJModeLivraison.prototype.executerAjax = function () {
            var select = $('.choixLivraison');
            $.ajax({
                url: 'index.php?controleur=panier&action=majModeLivraison',
                type: 'GET',
                data: 'choixLivraison=' + select.val()
            })
                .done(function (data, textStatus, jqXHR) {
                MAJModeLivraison.retournerResultat(data, textStatus, jqXHR);
            });
        };
        MAJModeLivraison.prototype.initialiser = function () {
            var selectModeLivraison = document.querySelector('#choixLivraison');
            if (selectModeLivraison != null) {
                selectModeLivraison.addEventListener('change', this.executerAjax_lier);
            }
        };
        return MAJModeLivraison;
    }());
    exports.MAJModeLivraison = MAJModeLivraison;
});
//# sourceMappingURL=MAJModeLivraison.js.map