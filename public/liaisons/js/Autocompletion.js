/**
 * @author Marie-Pierre Cardinal-Labrie <1463196@cegep-ste-foy.qc.ca>
 *
 * Ajax de l'autocomplétion du champ de recherche ---------------------------------------------------------------------------------------------
 */
define(["require", "exports"], function (require, exports) {
    "use strict";
    Object.defineProperty(exports, "__esModule", { value: true });
    var Autocompletion = /** @class */ (function () {
        function Autocompletion() {
            this.initialiser();
        }
        Autocompletion.prototype.executerAjax = function () {
            if ($('.recherche__filtres').val() == "auteur" || $('.recherche__filtres').val() == "0") {
                var entree = $('.recherche__input').val();
                $.ajax({
                    url: 'index.php?controleur=site&action=autocompleter',
                    type: 'GET',
                    data: "entree=" + entree,
                    dataType: 'html'
                })
                    .done(function (data, textStatus, jqXHR) {
                    Autocompletion.retournerResultat(data, textStatus, jqXHR);
                });
            }
        };
        Autocompletion.retournerResultat = function (data, textStatus, jqXHR) {
            if (data == "") {
                $(".recherche__resultats").html("<li class='recherche__resultats_item recherche__resultats_item--vide'>Aucun résultat</li>");
            }
            else {
                $(".recherche__resultats").html(data);
            }
            if ($(".recherche__input").val() == "") {
                $(".recherche__resultats").html("");
            }
        };
        Autocompletion.prototype.initialiser = function () {
            $('#champRecherche').on('input', this.executerAjax.bind(this));
        };
        return Autocompletion;
    }());
    exports.Autocompletion = Autocompletion;
});
//# sourceMappingURL=Autocompletion.js.map