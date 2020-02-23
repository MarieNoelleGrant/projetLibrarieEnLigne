define(["require", "exports"], function (require, exports) {
    "use strict";
    Object.defineProperty(exports, "__esModule", { value: true });
    var DispoCourriel = /** @class */ (function () {
        // CONSTRUCTEUR
        function DispoCourriel() {
            // ATTRIBUTS
            this.executerAjax_lier = null;
            this.executerAjax_lier = this.executerAjax.bind(this);
            this.initialiser();
        }
        // MÉTHODES
        DispoCourriel.prototype.initialiser = function () {
            document.getElementById("courriel").addEventListener("input", this.executerAjax_lier);
        };
        // Une fonction de retour (CALLBACK) qui va être appelé une fois qu'ajax va avoir fini
        DispoCourriel.retournerResultat = function (data, textStatus, jqXHR) {
            // console.log(data);
            var message = '';
            if (data == 0) {
                message = '<span class=\"icone icone--erreur\"></span>Cette adresse courriel est déjà asscociée à un compte existant.';
                $('#courriel').addClass('erreur');
                $('.dispoCourriel').html(message);
                DispoCourriel.courrielEstLibre = false;
            }
            else {
                $('#courriel').removeClass('erreur');
                DispoCourriel.courrielEstLibre = true;
                $('.dispoCourriel').html('');
            }
        };
        // AJAX !
        DispoCourriel.prototype.executerAjax = function (evenement) {
            // console.log('je passe dans ajax');
            $.ajax({
                url: 'index.php?controleur=client&action=dispoCourriel',
                type: 'GET',
                data: 'valCourriel=' + evenement.currentTarget.value,
                dataType: 'html'
            })
                .done(function (data, textStatus, jqXHR) {
                DispoCourriel.retournerResultat(data, textStatus, jqXHR); // envoie au callback
            });
            // console.log('Ceci affichera avant que la méthode retournerRésultat ne soit terminé');
            // console.log(evenement.currentTarget.value);
        };
        DispoCourriel.retournerSiCourrielEstLibre = function () {
            return DispoCourriel.courrielEstLibre;
        };
        DispoCourriel.courrielEstLibre = true;
        return DispoCourriel;
    }());
    exports.DispoCourriel = DispoCourriel;
});
//# sourceMappingURL=DispoCourriel.js.map