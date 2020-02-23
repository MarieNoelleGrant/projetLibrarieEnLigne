export class DispoCourriel {

    // ATTRIBUTS
    private executerAjax_lier = null;
    private static courrielEstLibre:boolean = true;

    // CONSTRUCTEUR
    public constructor(){
        this.executerAjax_lier = this.executerAjax.bind(this);
        this.initialiser();
    }

    // MÉTHODES
    private initialiser() {
        document.getElementById("courriel").addEventListener("input", this.executerAjax_lier);
    }

    // Une fonction de retour (CALLBACK) qui va être appelé une fois qu'ajax va avoir fini
    public static retournerResultat(data, textStatus, jqXHR){
        // console.log(data);
        let message = '';
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
    }

    // AJAX !
    private executerAjax(evenement)
    {
        // console.log('je passe dans ajax');
        $.ajax({
            url : 'index.php?controleur=client&action=dispoCourriel',
            type : 'GET',
            data : 'valCourriel=' + evenement.currentTarget.value,
            dataType : 'html'
        })
            .done(function (data, textStatus, jqXHR) { // reçois
                DispoCourriel.retournerResultat(data, textStatus, jqXHR);  // envoie au callback
            });
        // console.log('Ceci affichera avant que la méthode retournerRésultat ne soit terminé');
        // console.log(evenement.currentTarget.value);
    }

    public static retournerSiCourrielEstLibre():boolean {
        return DispoCourriel.courrielEstLibre;
    }

}