/**
 * @author Marie-Pierre Cardinal-Labrie <1463196@cegep-ste-foy.qc.ca>
 *
 * Ajax de l'autocomplétion du champ de recherche ---------------------------------------------------------------------------------------------
 */

export class Autocompletion {

    public constructor() {
        this.initialiser();
    }

    public executerAjax() {
        if ($('.recherche__filtres').val() == "auteur" || $('.recherche__filtres').val() == "0") {
            let entree = $('.recherche__input').val();
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
    }

    public static retournerResultat(data, textStatus, jqXHR) {
        if (data == "") {
            $(".recherche__resultats").html("<li class='recherche__resultats_item recherche__resultats_item--vide'>Aucun résultat</li>");
        }else {
            $(".recherche__resultats").html(data);
        }
        if($(".recherche__input").val() == "") {
            $(".recherche__resultats").html("");
        }
    }

    private initialiser() {
        $('#champRecherche').on('input', this.executerAjax.bind(this));
    }
}