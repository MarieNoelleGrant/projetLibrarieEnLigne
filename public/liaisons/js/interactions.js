var accordeonTrisMobile = {

    initialiser: function () {
        $(".categories__bouton").on('click', this.afficherCacherCategories.bind(this));
        $(".tris__bouton").on('click', this.afficherCacherTris.bind(this));
    },

    afficherCacherCategories: function () {
        $(".categories__contenu").toggleClass("visible");
        $(".categories__bouton").toggleClass("categories__bouton--actif");
    },

    afficherCacherTris: function () {
        $(".tris__contenu").toggleClass("visible");
        $(".tris__bouton").toggleClass("tris__bouton--actif");
    }
};

var accordeonInfos = {

    // refSectionInfosSec: $('.infosSecondaires__listeItem'),

    initialiser: function ()
    {
        $('.infosSecondaires__liste').children().not('.informations').addClass('infosSecondaires__listeItem--ferme');
        $('.infosSecondaires__liste').children().not('.informations').find('.icone--chevron').removeClass('icone--chevron-fermer');
        $('.infosSecondaires__onglet').on('click', this.afficherCacherInfos.bind(this));
    },

    afficherCacherInfos: function(evenement)
    {
        $(evenement.currentTarget).closest('.infosSecondaires__listeItem').toggleClass('infosSecondaires__listeItem--ferme');
        $(evenement.currentTarget).find('.icone--chevron').toggleClass('icone--chevron-fermer');
    }
};

var boutonsPanier = {

    refFormatAchat: $('.ajoutPanier__controle'),
    refBoutonAchat: $('.bouton__ajoutPanier'),

    initialiser: function ()
    {
        for(let intCpt=0; intCpt<2; intCpt++) {
            this.refFormatAchat[intCpt].addEventListener('focus', this.changerEtatBoutonsAchat.bind(this));
        }
        // Ajouts en javascript actif seulement, pour que le bouton soit actif et que la première option soit coché
        // si aucun javascript n'est activé
        $('#btnRadio__format_papier').removeAttr('checked').attr('tabindex', '-1');
        $('#btnRadio__format_elecro').attr('tabindex', '-1');
        this.refBoutonAchat.attr('disabled', 'disabled');

        document.querySelector('.ajoutPanier__zoneClic').addEventListener('click', this.afficherChoixFormat.bind(this));
    },

    changerEtatBoutonsAchat: function (evenement)
    {
        if($(evenement.currentTarget).closest('.ajoutPanier__format').hasClass('ajoutPanier__format--choisi')) {
            $(evenement.currentTarget).closest('.ajoutPanier__format').removeClass('ajoutPanier__format--choisi').removeAttr('checked');
            this.refBoutonAchat.attr('disabled', 'disabled');
        }
        else {
            $(evenement.currentTarget).closest('.ajoutPanier__format').addClass('ajoutPanier__format--choisi');
            $(evenement.currentTarget).closest('.ajoutPanier__format').siblings().removeClass('ajoutPanier__format--choisi');
            if ($('.ajoutPanier__msgChoixFormat').hasClass('displayNone')==false) {
                $('.ajoutPanier__msgChoixFormat').addClass('displayNone');
            }
            this.refBoutonAchat.removeAttr('disabled');
        }
    },

    afficherChoixFormat: function (evenement) {
        if (this.refBoutonAchat.attr('disabled')){
            $('.ajoutPanier__msgChoixFormat').removeClass('displayNone');
        }
    }
};

var rechercheMobile = {

    // cacherToogle: function () {
    //     // Initial state
    //     var scrollPos = 0;
    //     // adding scroll event
    //     window.addEventListener('scroll', function(){
    //         // detects new state and compares it with the new one
    //         if ((document.body.getBoundingClientRect()).top > scrollPos)
    //             $('.recherche__form').removeClass('cache');
    //         else
    //             $('.recherche__form').addClass('cache');
    //         // saves the new position for iteration.
    //         scrollPos = (document.body.getBoundingClientRect()).top;
    //     });
    // }

};