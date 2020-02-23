
export class CoupsCoeurInfo {

    // ATTRIBUTS
    private executerAjax_lier = null;

    // CONSTRUCTEUR
    public constructor(){
        this.executerAjax_lier = this.executerAjax.bind(this);
        this.initialiser();
    }

    // MÉTHODES
    private initialiser() {
        let arrCoupsCoeur = Array.apply(null, document.querySelectorAll('.coupCoeur__grid_item'));
        for (let i = 0; i <= arrCoupsCoeur.length-1; i++) {
            arrCoupsCoeur[i].addEventListener('focus', this.executerAjax_lier);
        }
    }

    // Une fonction de retour (CALLBACK) qui va être appelé une fois qu'ajax va avoir fini
    public static retournerResultat(data, textStatus, jqXHR){
        console.log(data);
        let arrInfoCoupCoeur = JSON.parse(data);

        let livre = $('#'+arrInfoCoupCoeur["id"]);
        // livre.addClass('coupCoeur--selected');
        console.log(livre);

        $('.coupCoeur__grid_item--desc').addClass('coupCoeur__info').html('' +
            '<p class="h4 coupCoeur__info_titre">' + arrInfoCoupCoeur["titre"] + '</p>' +
            '<p class="coupCoeur__info_auteurs">' + arrInfoCoupCoeur["auteurs"] + '</p>' +
            '<p class="coupCoeur__info_prix">' + arrInfoCoupCoeur["prix"] + '</p>' +
            '<p class="coupCoeur__info_categories">' + arrInfoCoupCoeur["categories"] + '</p>' +
            '<a href="index.php?controleur=livre&action=fiche&idLivre=' + arrInfoCoupCoeur["id"] + '&pagePrecedente=Accueil' + '" class="bouton bouton--principal">Voir la fiche</a>'
        );
    }

    // AJAX !
    private executerAjax(evenement)
    {
        $('.coupCoeur__grid_item').removeClass('coupCoeur__grid_item--selected');
        let livre = $(evenement.currentTarget);
        livre.addClass('coupCoeur__grid_item--selected');

        // console.log('je passe dans ajax');
        $.ajax({
            url : 'index.php?controleur=site&action=infoCoupCoeur',
            type : 'GET',
            data : 'dataID=' + livre.attr('id'),
            dataType : 'html'
        })
            .done(function (data, textStatus, jqXHR) { // reçois
                CoupsCoeurInfo.retournerResultat(data, textStatus, jqXHR);  // envoie au callback
            });
    }

}