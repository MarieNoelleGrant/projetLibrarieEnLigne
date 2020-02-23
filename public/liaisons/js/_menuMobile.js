/**
 * @file Un menu simple, responsive moile.
 * @author @evefevrier <eve.fevrier@cegep-ste-foy.qc.ca>
 * @author Yves Hélie <yves.helie@cegep-ste-foy.qc.ca>
 * @author Christine Daneau-Pelletier <christine.daneau.pelletier@gmail.com>
 * @version 1.2.4
 *
 */

//*******************
// Déclaration d'objet(s)
//*******************

var menuMobile = {

  refMenu: $('.menu'),

  btnMenu : null,
  btnAccordeon : null,

  lblMenu : 'Menu',
  lblOuvrir : 'Ouvrir',
  lblFermer : 'Fermer',

  configurerNav: function ()
  {
    // Création du libellé qui sera utilisé de base pour le bouton
    var libelleMenu = $('<span>').addClass('screen-reader-only').addClass('libelle');
    $(libelleMenu).html(this.lblMenu);

    // Création du bouton
    this.btnMenu = $('<button>');
    this.btnMenu.addClass('menu__btnMenu');
    this.btnMenu.addClass('menu__btnMenu--ferme');
    this.btnMenu.append(libelleMenu);

    // On ajoute le bouton pour le menu mobile
    this.refMenu.prepend(this.btnMenu);

    // On ajoute la classe --ferme au menu en général; par défaut il est caché avec JS
    this.refMenu.find('.menu__liste').addClass('menu__liste--ferme');

    // Création de l'écouteur d'événement pour le bouton du menu mobile
    this.refMenu.find(".menu__btnMenu").on("click",this.ouvrirFermerMenu.bind(this));
  },

  /**
   * Méthode pour basculer l'affichage du menu mobile en se basant sur la classe --ferme
   * @param evenement
   */
  ouvrirFermerMenu: function (evenement)
  {
    // Bascule de l'état du bouton
    $(evenement.currentTarget).toggleClass('menu__btnMenu--ferme');

    // Bascule de l'état du menu
    $(evenement.currentTarget).next('.menu__liste').toggleClass('menu__liste--ferme');

    // Changement du libellé du bouton du menu mobile
    if($(evenement.currentTarget).hasClass('menu__btnMenu--ferme')){
      $(evenement.currentTarget).find('.libelle').html(this.lblMenu);
    }
    else {
      $(evenement.currentTarget).find('.libelle').html(this.lblFermer);
    }
  }
};