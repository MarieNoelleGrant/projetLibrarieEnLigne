

/**
 * @author Marie-Noëlle Grant <m.noelle.grant@gmail.com>
 *
 * VALIDATIONS JAVASCRIPT ---------------------------------------------------------------------------------------------
 */

import {MAJQuantite} from './MAJQuantite';
import {DispoCourriel} from "./DispoCourriel";
import {MAJModeLivraison} from "./MAJModeLivraison";
import {Autocompletion} from "./Autocompletion";
import {CoupsCoeurInfo} from "./CoupsCoeurInfo";

export class Validations {

    // ATTRIBUTS
    private objMessages: JSON;
    //private refAjaxDispoCourriel:DispoCourriel;

    // -- Éléments de formulaire à valider
    private refPrenom: HTMLInputElement = null;
    private refNom: HTMLInputElement = null;
    private refTelephone: HTMLInputElement = null;
    private refAdresse: HTMLInputElement = null;
    private refVille: HTMLInputElement = null;
    private refProvince: HTMLInputElement = null;
    private refCodePostal: HTMLInputElement = null;

    private refCourriel: HTMLInputElement = null;
    private refMDP: HTMLInputElement = null;
    private refToggleMDP: HTMLInputElement = null;

    private refModePayement: Array<HTMLInputElement> = null;
    private refNomSurCarte: HTMLInputElement = null;
    private refNumeroCarte: HTMLInputElement = null;
    private refCodeSecurite: HTMLInputElement = null;
    private refMoisExpiration: HTMLInputElement = null;
    private refAnneeExpiration: HTMLInputElement = null;
    private contenuMsgErreurExpiration: Array<string> = [];


    // -- Liaisons pour le bind du this
    private validerChampTexte_lier:any = null;
    private validerSelect_lier:any = null;
    private validerSelectDate_lier:any = null;

    private validerDateExpiration_lier:any = null;

    private modifierAideSaisie_lier:any = null;
    private toggleMDP_lier:any = null;



    // Constructeur
    // ** avait en commentaire : objJSON: JSOn, mais n'était jamais utilisé
    public constructor() {
        document.querySelector('form').noValidate = true;

        // Liaison des méthodes
        this.validerChampTexte_lier = this.validerChampTexte.bind(this);
        this.validerSelect_lier = this.validerSelect.bind(this);
        this.validerSelectDate_lier = this.validerSelectDate.bind(this);

        this.modifierAideSaisie_lier = this.modifierAideSaisie.bind(this);

        this.validerDateExpiration_lier = this.validerDateExpiration.bind(this);

        this.toggleMDP_lier = this.toggleMDP.bind(this);

        this.contenuMsgErreurExpiration['icone'] = '';
        this.contenuMsgErreurExpiration['dateComplete'] = '';
        this.contenuMsgErreurExpiration['mois_expiration'] = '';
        this.contenuMsgErreurExpiration['annee_expiration'] = '';


        fetch("liaisons/js/objMessages.json")
            .then(response => {
                return response.json();
            })
            .then(response => {
                this.objMessages = response;
                // console.log("objJSON", this.objMessages);
                switch(document.querySelector('main').classList[0]) {
                    case 'connexion': this.initialiserClientConnexion();
                        break;
                    case 'creer': this.initialiserClientCreer();
                        break;
                    case 'livraison': this.initialiserLivraison();
                        break;
                    case 'facturation': this.initialiserFacturation();
                        break;
                }
            })
            .catch(error => {
                console.log(error);
            });
    }

    // ***********************************************************************************
    // Méthodes d'initialisation
    // ***********************************************************************************

    // Initialisation pour la page de création de compte
    // -----------------------------------------------------------------------------------
    private initialiserClientCreer():void {
        // 1) Initialisation des variables pour les champs
        this.refPrenom = document.querySelector('#prenom');
        this.refNom = document.querySelector('#nom');
        this.refCourriel = document.querySelector('#courriel');
        this.refTelephone = document.querySelector('#telephone');
        this.refMDP = document.querySelector('#mdp');
        this.refToggleMDP = document.querySelector('#toggleMDP');

        // 2) Initialisation des écouteurs d'événement
        this.refPrenom.addEventListener('blur',this.validerChampTexte_lier);
        this.refNom.addEventListener('blur',this.validerChampTexte_lier);
        this.refCourriel.addEventListener('blur', this.validerChampTexte_lier);
        this.refTelephone.addEventListener('blur',this.validerChampTexte_lier);
        this.refMDP.addEventListener('blur',this.validerChampTexte_lier);
        this.refMDP.addEventListener('input', this.modifierAideSaisie_lier);
        this.refToggleMDP.addEventListener('click', this.toggleMDP_lier);

    }

    // Initialisation pour la page de connexion
    // -----------------------------------------------------------------------------------
    private initialiserClientConnexion():void {
        // 1) Initialisation des variables pour les champs
        this.refCourriel = document.querySelector('#courriel');
        // this.refMDP = document.querySelector('#mdp');
        this.refToggleMDP = document.querySelector('#toggleMDP');

        // 2) Initialisation des écouteurs d'événement
        this.refCourriel.addEventListener('blur', this.validerChampTexte_lier);
        // this.refMDP.addEventListener('blur',this.validerChampTexte_lier);
        this.refToggleMDP.addEventListener('click', this.toggleMDP_lier);
    }

    // Initialisation pour la page de livraison
    // -----------------------------------------------------------------------------------
    private initialiserLivraison():void {
        // 1) Initialisation des variables pour les champs
        this.refPrenom = document.querySelector('#prenom');
        this.refNom = document.querySelector('#nom');
        this.refAdresse = document.querySelector('#adresse');
        this.refVille = document.querySelector('#ville');
        this.refProvince = document.querySelector('#provinces');
        this.refCodePostal = document.querySelector('#postal');

        // 2) Initialisation des écouteurs d'événement
        this.refPrenom.addEventListener('blur',this.validerChampTexte_lier);
        this.refNom.addEventListener('blur',this.validerChampTexte_lier);
        this.refAdresse.addEventListener('blur',this.validerChampTexte_lier);
        this.refVille.addEventListener('blur',this.validerChampTexte_lier);
        this.refProvince.addEventListener('blur',this.validerSelect_lier);
        this.refCodePostal.addEventListener('blur',this.validerChampTexte_lier);

    }

    // Initialisation pour la page de facturation
    // -----------------------------------------------------------------------------------
    private initialiserFacturation():void {
        // 1) Initialisation des variables pour les champs
        this.refNomSurCarte = document.querySelector('#nom_complet');
        this.refNumeroCarte = document.querySelector('#numero_carte');
        this.refCodeSecurite = document.querySelector('#numero_securite');
        this.refMoisExpiration = document.querySelector('#mois_expiration');
        this.refAnneeExpiration = document.querySelector('#annee_expiration');
        this.refAdresse = document.querySelector('#adresse');
        this.refVille = document.querySelector('#ville');
        this.refProvince = document.querySelector('#provinces');
        this.refCodePostal = document.querySelector('#postal');

        // 2) Initialisation des écouteurs d'événement
        this.refNomSurCarte.addEventListener('blur', this.validerChampTexte_lier);
        this.refNumeroCarte.addEventListener('blur', this.validerChampTexte_lier);
        this.refCodeSecurite.addEventListener('blur', this.validerChampTexte_lier);
        this.refMoisExpiration.addEventListener('blur', this.validerSelectDate_lier);
        this.refAnneeExpiration.addEventListener('blur', this.validerSelectDate_lier);
        this.refAdresse.addEventListener('blur', this.validerChampTexte_lier);
        this.refVille.addEventListener('blur', this.validerChampTexte_lier);
        this.refProvince.addEventListener('blur', this.validerSelect_lier);
        this.refCodePostal.addEventListener('blur', this.validerChampTexte_lier);
    }

    // ***********************************************************************************
    // Méthodes de validation
    // ***********************************************************************************

    /**
     * Validations pour les différents champs texte : Nom, Prénom, Adresse, Ville, Code Postal, Courriel, Mot de passe,
     * Numéro de carte de crédit, Numéro de sécurité et le Nom sur la carte de crédit.
     * Fait la vérification en trois étape : si vide, si pattern non respecté, si ok.
     * Change le message d'erreur selon
     * @param evenement
     */
    private validerChampTexte(evenement):void {
        const refChamp = evenement.currentTarget;
        let $msgErreur = $(refChamp).closest('div').siblings('.msgErreur');
        let texteMsgErreur:string = "";

        if (this.validerSiVide(refChamp)===true) {
            texteMsgErreur = '<span class="icone icone--erreur"></span>'+this.objMessages[refChamp.name]['vide'];
            $(refChamp).addClass('erreur');
        }
        else {
            if (refChamp.name=='courriel') {
                let courrielDispo = DispoCourriel.retournerSiCourrielEstLibre();
                if (courrielDispo==true) {
                    if (this.validerPattern(refChamp, "")===false) {
                        texteMsgErreur = '<span class="icone icone--erreur"></span>'+this.objMessages[refChamp.name]['motif'];
                        $(refChamp).addClass('erreur');
                    }
                    else {
                        texteMsgErreur = '<span class="icone icone--crochet"></span>';
                        $(refChamp).removeClass('erreur');
                        $('.dispoCourriel').html('');
                    }
                }
                else {
                    texteMsgErreur = $msgErreur.html();
                }
            }
            else {
                if (this.validerPattern(refChamp, "")===false) {
                    texteMsgErreur = '<span class="icone icone--erreur"></span>'+this.objMessages[refChamp.name]['motif'];
                    $(refChamp).addClass('erreur');
                }
                else {
                    texteMsgErreur = '<span class="icone icone--crochet"></span>';
                    $(refChamp).removeClass('erreur');
                }
            }
        }

        $msgErreur.html(texteMsgErreur);
    }

    /**
     * Validations pour les sélect spécifiques pour le Mois et Année d'expiration de la carte de crédit.
     * Si champ correspondant au mois ou année d'expiration, vérifie si le champ select connexe est aussi rempli. Si oui,
     * fait une validation de la date d'expiration complète par la méthode utilitaire validerDateExpiration();
     * @param evenement
     */
    private validerSelectDate(evenement):void {
        const refSelect = evenement.currentTarget;
        let $msgErreur = $('.expirationCarte__flex').siblings('.msgErreur');

        if (refSelect.value == '') {
            $(refSelect).addClass('erreur');
            this.contenuMsgErreurExpiration['icone'] = '<span class="icone icone--erreur"></span>';
            this.contenuMsgErreurExpiration[refSelect.name] = this.objMessages[refSelect.name]['vide'] + '<br/>';
        }
        else {
            this.contenuMsgErreurExpiration[refSelect.name] = '';
            $(refSelect).removeClass('erreur');
            let refAutreSelect = $(refSelect).parent().siblings('div').find('select');
            if (refAutreSelect.val()!='') {
                let resultatValidationDate = null;
                if (refSelect.name=='mois_expiration') {
                    resultatValidationDate = this.validerDateExpiration($(refSelect).val(), refAutreSelect.val(), '01');
                }
                else {
                    resultatValidationDate = this.validerDateExpiration(refAutreSelect.val(), $(refSelect).val(), '01');
                }

                if (resultatValidationDate == false) {
                    this.contenuMsgErreurExpiration['icone'] = '<span class="icone icone--erreur"></span>';
                    this.contenuMsgErreurExpiration['dateComplete'] = this.objMessages['dateComplete']['motif'] + '<br/>';
                    $(refSelect).addClass('erreur');
                    $(refAutreSelect).addClass('erreur');
                }
                else {
                    this.contenuMsgErreurExpiration['dateComplete'] = '';
                    $(refSelect).removeClass('erreur');
                    $(refAutreSelect).removeClass('erreur');
                    this.contenuMsgErreurExpiration['icone'] = '<span class="icone icone--crochet"></span>';
                }
            }
        }
        console.log(this.contenuMsgErreurExpiration);
        $msgErreur.html(this.contenuMsgErreurExpiration['icone'] + '<span class=msgDateComplete>'+this.contenuMsgErreurExpiration['mois_expiration'] + this.contenuMsgErreurExpiration['annee_expiration'] + this.contenuMsgErreurExpiration['dateComplete']+'</span>');
    }

    /**
     * Validations pour les différents champs de type select comme : Province.
     * @param evenement
     */
    private validerSelect(evenement):void {
        const refSelect = evenement.currentTarget;
        let $msgErreur = $(refSelect).closest('div').siblings('.msgErreur');

        if (refSelect.value == '0'||refSelect.value == '') {
            $(refSelect).addClass('erreur');
            $msgErreur.html('<span class="icone icone--erreur"></span>'+this.objMessages[refSelect.name]['vide']);
        }
        else {
            $(refSelect).removeClass('erreur');
            $msgErreur.html('<span class="icone icone--crochet"></span>');
        }
    }

    /**
     * Modification de l'état visuel du mot de passe en changeant l'attribut [type] du input.
     * Vérifie la présence ou non de la classe .icone__oeil--ouvert (donc si mot de passe caché).
     * Change l'attribut [type] du input, l'état de l'icone avec une classe, et le texte présent pour les lecteurs d'écran.
     */

    private toggleMDP(evenement):void {
        let inputMDP = $('#mdp')
        if (inputMDP.attr('type') == "password") {
            inputMDP.attr('type', 'text');
            $('.icone--oeil').addClass('icone--oeil-fermer').removeClass('icone--oeil-ouvert');
            $('.toggleMDP__label_tx').text('Cacher');
        }
        else {
            inputMDP.attr('type', 'password');
            $('.icone--oeil').removeClass('icone--oeil-fermer').addClass('icone--oeil-ouvert');
            $('.toggleMDP__label_tx').text('Afficher');
        }
    }

    /**
     * Modification des indices pour la sécurité du mot de passe
     * Vérifie individuellement les quatre conditions minimales.
     * Si elles sont atteintes, rend invisible la consigne correspondante.
     * ** Si toutes les conditions sont atteintes, cache la section d'aide.
     * ** Si une erreur est faite de nouveau, réaffiche la section.
     */

    private modifierAideSaisie(evenement):void {
        let $refListeSecurite = $('.aideSaisie__liste');
        let refChampMDP = evenement.currentTarget;
        let refContenuMDP = evenement.currentTarget.value;

        // 1. VÉRIFICATION POUR LES : minuscules
        // ---------------------------------------------------------------------------------------------------------------------------------
        if (this.validerPattern(refChampMDP,'(?=.*[a-z])')) {
            $refListeSecurite.find('.aideSaisie__minus').addClass('visuallyhidden');
        }
        else {
            $refListeSecurite.find('.aideSaisie__minus').removeClass('visuallyhidden');
        }
        // 2. VÉRIFICATION POUR LES : majuscules
        // ---------------------------------------------------------------------------------------------------------------------------------
        if (this.validerPattern(refChampMDP,'(?=.*[A-Z])')) {
            $refListeSecurite.find('.aideSaisie__majus').addClass('visuallyhidden');
        }
        else {
            $refListeSecurite.find('.aideSaisie__majus').removeClass('visuallyhidden');

        }
        // 3. VÉRIFICATION POUR LES : chiffres
        // ---------------------------------------------------------------------------------------------------------------------------------
        if (this.validerPattern(refChampMDP,'(?=.*[0-9])')) {
            $refListeSecurite.find('.aideSaisie__num').addClass('visuallyhidden');
        }
        else {
            $refListeSecurite.find('.aideSaisie__num').removeClass('visuallyhidden');


        }
        // 4. VÉRIFICATION POUR LA : longeur
        // ---------------------------------------------------------------------------------------------------------------------------------
        if (refContenuMDP.length >= 8 && refContenuMDP.length <= 16) {
            $refListeSecurite.find('.aideSaisie__size').addClass('visuallyhidden');
        }
        else {
            $refListeSecurite.find('.aideSaisie__size').removeClass('visuallyhidden');
        }

        // *** Pour faire disparaître/réapparaître la section aide selon la validation du mot de passe
        // ---------------------------------------------------------------------------------------------------------------------------------
        if ($refListeSecurite.children('.visuallyhidden').length===4) {
            $('.aideSaisie').addClass('visuallyhidden');
        }
        else {
            $('.aideSaisie').removeClass('visuallyhidden');
        }
    }


    // Méthodes utilitaires
    /**
     * Si champ est vide, retourne TRUE
     * @param refChamp
     * @return valeurRetournee
     */
    private validerSiVide(refChamp):boolean {
        let valeurRetournee:boolean = false;
        if (refChamp.value === "") {
            valeurRetournee = true;
        }
        return valeurRetournee;
    }

    /**
     * Vérifie premièrement si l'argument du motif est vide. Si oui, va chercher le motif dans le HTMl et concatene elements de sécurité.
     * Si non, prend le motif fourni en argument.
     * Ensuite, vérifie si le motif est respecté. Si oui, retourne TRUE
     * @param element
     * @param motif
     * @return booléen du régex vérifié
     */
    private validerPattern(element:HTMLInputElement, motif:string):boolean {
        let regexp:RegExp = null;
        if (motif === "") {
            motif = element.pattern;
            regexp = new RegExp("^" + motif + "$");
        }
        else {
            regexp = new RegExp(motif);
        }
        return regexp.test(element.value);
    }

    /**
     * Petite méthode pour valider que la date d'expiration de la carte n'est pas expiré!
     * @param refMois
     * @param refAnnee
     * @param refJour -- Dans notre contexte, correspond toujours à '01', mais pour que la méthode soit la plus passe-partout possible, l'argument a été laissé
     * @return différence entre les deux dates. Résultat : false si la date est expirée, true si elle est toujours bonne.
     */
    private validerDateExpiration(refMois,refAnnee,refJour):boolean {
        let dateAujourdhui = new Date();
        let dateExpiration = new Date([refAnnee, refMois, refJour].join('-'));

        return dateExpiration.getTime() >= dateAujourdhui.getTime();
    }
}
