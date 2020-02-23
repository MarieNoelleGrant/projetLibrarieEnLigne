/**
 * @author Marie-Noëlle Grant <m.noelle.grant@gmail.com>
 *
 * VALIDATIONS JAVASCRIPT ---------------------------------------------------------------------------------------------
 */
define(["require", "exports", "./DispoCourriel"], function (require, exports, DispoCourriel_1) {
    "use strict";
    Object.defineProperty(exports, "__esModule", { value: true });
    var Validations = /** @class */ (function () {
        // Constructeur
        // ** avait en commentaire : objJSON: JSOn, mais n'était jamais utilisé
        function Validations() {
            var _this = this;
            //private refAjaxDispoCourriel:DispoCourriel;
            // -- Éléments de formulaire à valider
            this.refPrenom = null;
            this.refNom = null;
            this.refTelephone = null;
            this.refAdresse = null;
            this.refVille = null;
            this.refProvince = null;
            this.refCodePostal = null;
            this.refCourriel = null;
            this.refMDP = null;
            this.refToggleMDP = null;
            this.refModePayement = null;
            this.refNomSurCarte = null;
            this.refNumeroCarte = null;
            this.refCodeSecurite = null;
            this.refMoisExpiration = null;
            this.refAnneeExpiration = null;
            this.contenuMsgErreurExpiration = [];
            // -- Liaisons pour le bind du this
            this.validerChampTexte_lier = null;
            this.validerSelect_lier = null;
            this.validerSelectDate_lier = null;
            this.validerDateExpiration_lier = null;
            this.modifierAideSaisie_lier = null;
            this.toggleMDP_lier = null;
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
                .then(function (response) {
                return response.json();
            })
                .then(function (response) {
                _this.objMessages = response;
                // console.log("objJSON", this.objMessages);
                switch (document.querySelector('main').classList[0]) {
                    case 'connexion':
                        _this.initialiserClientConnexion();
                        break;
                    case 'creer':
                        _this.initialiserClientCreer();
                        break;
                    case 'livraison':
                        _this.initialiserLivraison();
                        break;
                    case 'facturation':
                        _this.initialiserFacturation();
                        break;
                }
            })
                .catch(function (error) {
                console.log(error);
            });
        }
        // ***********************************************************************************
        // Méthodes d'initialisation
        // ***********************************************************************************
        // Initialisation pour la page de création de compte
        // -----------------------------------------------------------------------------------
        Validations.prototype.initialiserClientCreer = function () {
            // 1) Initialisation des variables pour les champs
            this.refPrenom = document.querySelector('#prenom');
            this.refNom = document.querySelector('#nom');
            this.refCourriel = document.querySelector('#courriel');
            this.refTelephone = document.querySelector('#telephone');
            this.refMDP = document.querySelector('#mdp');
            this.refToggleMDP = document.querySelector('#toggleMDP');
            // 2) Initialisation des écouteurs d'événement
            this.refPrenom.addEventListener('blur', this.validerChampTexte_lier);
            this.refNom.addEventListener('blur', this.validerChampTexte_lier);
            this.refCourriel.addEventListener('blur', this.validerChampTexte_lier);
            this.refTelephone.addEventListener('blur', this.validerChampTexte_lier);
            this.refMDP.addEventListener('blur', this.validerChampTexte_lier);
            this.refMDP.addEventListener('input', this.modifierAideSaisie_lier);
            this.refToggleMDP.addEventListener('click', this.toggleMDP_lier);
        };
        // Initialisation pour la page de connexion
        // -----------------------------------------------------------------------------------
        Validations.prototype.initialiserClientConnexion = function () {
            // 1) Initialisation des variables pour les champs
            this.refCourriel = document.querySelector('#courriel');
            // this.refMDP = document.querySelector('#mdp');
            this.refToggleMDP = document.querySelector('#toggleMDP');
            // 2) Initialisation des écouteurs d'événement
            this.refCourriel.addEventListener('blur', this.validerChampTexte_lier);
            // this.refMDP.addEventListener('blur',this.validerChampTexte_lier);
            this.refToggleMDP.addEventListener('click', this.toggleMDP_lier);
        };
        // Initialisation pour la page de livraison
        // -----------------------------------------------------------------------------------
        Validations.prototype.initialiserLivraison = function () {
            // 1) Initialisation des variables pour les champs
            this.refPrenom = document.querySelector('#prenom');
            this.refNom = document.querySelector('#nom');
            this.refAdresse = document.querySelector('#adresse');
            this.refVille = document.querySelector('#ville');
            this.refProvince = document.querySelector('#provinces');
            this.refCodePostal = document.querySelector('#postal');
            // 2) Initialisation des écouteurs d'événement
            this.refPrenom.addEventListener('blur', this.validerChampTexte_lier);
            this.refNom.addEventListener('blur', this.validerChampTexte_lier);
            this.refAdresse.addEventListener('blur', this.validerChampTexte_lier);
            this.refVille.addEventListener('blur', this.validerChampTexte_lier);
            this.refProvince.addEventListener('blur', this.validerSelect_lier);
            this.refCodePostal.addEventListener('blur', this.validerChampTexte_lier);
        };
        // Initialisation pour la page de facturation
        // -----------------------------------------------------------------------------------
        Validations.prototype.initialiserFacturation = function () {
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
        };
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
        Validations.prototype.validerChampTexte = function (evenement) {
            var refChamp = evenement.currentTarget;
            var $msgErreur = $(refChamp).closest('div').siblings('.msgErreur');
            var texteMsgErreur = "";
            if (this.validerSiVide(refChamp) === true) {
                texteMsgErreur = '<span class="icone icone--erreur"></span>' + this.objMessages[refChamp.name]['vide'];
                $(refChamp).addClass('erreur');
            }
            else {
                if (refChamp.name == 'courriel') {
                    var courrielDispo = DispoCourriel_1.DispoCourriel.retournerSiCourrielEstLibre();
                    if (courrielDispo == true) {
                        if (this.validerPattern(refChamp, "") === false) {
                            texteMsgErreur = '<span class="icone icone--erreur"></span>' + this.objMessages[refChamp.name]['motif'];
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
                    if (this.validerPattern(refChamp, "") === false) {
                        texteMsgErreur = '<span class="icone icone--erreur"></span>' + this.objMessages[refChamp.name]['motif'];
                        $(refChamp).addClass('erreur');
                    }
                    else {
                        texteMsgErreur = '<span class="icone icone--crochet"></span>';
                        $(refChamp).removeClass('erreur');
                    }
                }
            }
            $msgErreur.html(texteMsgErreur);
        };
        /**
         * Validations pour les sélect spécifiques pour le Mois et Année d'expiration de la carte de crédit.
         * Si champ correspondant au mois ou année d'expiration, vérifie si le champ select connexe est aussi rempli. Si oui,
         * fait une validation de la date d'expiration complète par la méthode utilitaire validerDateExpiration();
         * @param evenement
         */
        Validations.prototype.validerSelectDate = function (evenement) {
            var refSelect = evenement.currentTarget;
            var $msgErreur = $('.expirationCarte__flex').siblings('.msgErreur');
            if (refSelect.value == '') {
                $(refSelect).addClass('erreur');
                this.contenuMsgErreurExpiration['icone'] = '<span class="icone icone--erreur"></span>';
                this.contenuMsgErreurExpiration[refSelect.name] = this.objMessages[refSelect.name]['vide'] + '<br/>';
            }
            else {
                this.contenuMsgErreurExpiration[refSelect.name] = '';
                $(refSelect).removeClass('erreur');
                var refAutreSelect = $(refSelect).parent().siblings('div').find('select');
                if (refAutreSelect.val() != '') {
                    var resultatValidationDate = null;
                    if (refSelect.name == 'mois_expiration') {
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
            $msgErreur.html(this.contenuMsgErreurExpiration['icone'] + '<span class=msgDateComplete>' + this.contenuMsgErreurExpiration['mois_expiration'] + this.contenuMsgErreurExpiration['annee_expiration'] + this.contenuMsgErreurExpiration['dateComplete'] + '</span>');
        };
        /**
         * Validations pour les différents champs de type select comme : Province.
         * @param evenement
         */
        Validations.prototype.validerSelect = function (evenement) {
            var refSelect = evenement.currentTarget;
            var $msgErreur = $(refSelect).closest('div').siblings('.msgErreur');
            if (refSelect.value == '0' || refSelect.value == '') {
                $(refSelect).addClass('erreur');
                $msgErreur.html('<span class="icone icone--erreur"></span>' + this.objMessages[refSelect.name]['vide']);
            }
            else {
                $(refSelect).removeClass('erreur');
                $msgErreur.html('<span class="icone icone--crochet"></span>');
            }
        };
        /**
         * Modification de l'état visuel du mot de passe en changeant l'attribut [type] du input.
         * Vérifie la présence ou non de la classe .icone__oeil--ouvert (donc si mot de passe caché).
         * Change l'attribut [type] du input, l'état de l'icone avec une classe, et le texte présent pour les lecteurs d'écran.
         */
        Validations.prototype.toggleMDP = function (evenement) {
            var inputMDP = $('#mdp');
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
        };
        /**
         * Modification des indices pour la sécurité du mot de passe
         * Vérifie individuellement les quatre conditions minimales.
         * Si elles sont atteintes, rend invisible la consigne correspondante.
         * ** Si toutes les conditions sont atteintes, cache la section d'aide.
         * ** Si une erreur est faite de nouveau, réaffiche la section.
         */
        Validations.prototype.modifierAideSaisie = function (evenement) {
            var $refListeSecurite = $('.aideSaisie__liste');
            var refChampMDP = evenement.currentTarget;
            var refContenuMDP = evenement.currentTarget.value;
            // 1. VÉRIFICATION POUR LES : minuscules
            // ---------------------------------------------------------------------------------------------------------------------------------
            if (this.validerPattern(refChampMDP, '(?=.*[a-z])')) {
                $refListeSecurite.find('.aideSaisie__minus').addClass('visuallyhidden');
            }
            else {
                $refListeSecurite.find('.aideSaisie__minus').removeClass('visuallyhidden');
            }
            // 2. VÉRIFICATION POUR LES : majuscules
            // ---------------------------------------------------------------------------------------------------------------------------------
            if (this.validerPattern(refChampMDP, '(?=.*[A-Z])')) {
                $refListeSecurite.find('.aideSaisie__majus').addClass('visuallyhidden');
            }
            else {
                $refListeSecurite.find('.aideSaisie__majus').removeClass('visuallyhidden');
            }
            // 3. VÉRIFICATION POUR LES : chiffres
            // ---------------------------------------------------------------------------------------------------------------------------------
            if (this.validerPattern(refChampMDP, '(?=.*[0-9])')) {
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
            if ($refListeSecurite.children('.visuallyhidden').length === 4) {
                $('.aideSaisie').addClass('visuallyhidden');
            }
            else {
                $('.aideSaisie').removeClass('visuallyhidden');
            }
        };
        // Méthodes utilitaires
        /**
         * Si champ est vide, retourne TRUE
         * @param refChamp
         * @return valeurRetournee
         */
        Validations.prototype.validerSiVide = function (refChamp) {
            var valeurRetournee = false;
            if (refChamp.value === "") {
                valeurRetournee = true;
            }
            return valeurRetournee;
        };
        /**
         * Vérifie premièrement si l'argument du motif est vide. Si oui, va chercher le motif dans le HTMl et concatene elements de sécurité.
         * Si non, prend le motif fourni en argument.
         * Ensuite, vérifie si le motif est respecté. Si oui, retourne TRUE
         * @param element
         * @param motif
         * @return booléen du régex vérifié
         */
        Validations.prototype.validerPattern = function (element, motif) {
            var regexp = null;
            if (motif === "") {
                motif = element.pattern;
                regexp = new RegExp("^" + motif + "$");
            }
            else {
                regexp = new RegExp(motif);
            }
            return regexp.test(element.value);
        };
        /**
         * Petite méthode pour valider que la date d'expiration de la carte n'est pas expiré!
         * @param refMois
         * @param refAnnee
         * @param refJour -- Dans notre contexte, correspond toujours à '01', mais pour que la méthode soit la plus passe-partout possible, l'argument a été laissé
         * @return différence entre les deux dates. Résultat : false si la date est expirée, true si elle est toujours bonne.
         */
        Validations.prototype.validerDateExpiration = function (refMois, refAnnee, refJour) {
            var dateAujourdhui = new Date();
            var dateExpiration = new Date([refAnnee, refMois, refJour].join('-'));
            return dateExpiration.getTime() >= dateAujourdhui.getTime();
        };
        return Validations;
    }());
    exports.Validations = Validations;
});
//# sourceMappingURL=Validations.js.map