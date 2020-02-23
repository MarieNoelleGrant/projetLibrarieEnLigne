<div class="conteneur">
    <a href="index.php?controleur=site&action=accueil" class="menu__logo ">
        <img src="liaisons/images/logo.svg" alt="logo de la librairie Traces">
    </a>

    {{-- Navigation principale - Rappel --}}
    <div class="rappelNav tableOnly">
{{--        <p class="h3">Navigation</p>--}}
        <ul class="menu__principal menu__liste">
            <li class="menu__liste_item">
                <a href="index.php?controleur=livre&action=index" class="menu__lien bouton--lien">Livres</a>
            </li>
            <li class="menu__liste_item">
                <a href="#" class="menu__lien bouton--lien">Meilleurs vendeurs</a>
            </li>
            <li class="menu__liste_item">
                <a href="index.php?controleur=site&action=apropos" class="menu__lien bouton--lien">Découvrir Traces</a>
            </li>
            <li class="menu__liste_item">
                <a href="#" class="menu__lien bouton--lien">Auteurs</a>
            </li>
        </ul>
    </div>

    {{-- Informations de contact --}}
    <div id="contact" class="contact">
        <p class="h3">Nous joindre</p>
        <p class="map">
            <svg class="icone--joindre" aria-hidden="true">
                <use xlink:href="#carte"/>
            </svg>
            <span class="screen-reader-only">carte</span>
            <span class="map__tx">Trouver un magasin</span>
        </p>
        <p class="courriel">
            <svg class="icone--joindre" aria-hidden="true">
                <use xlink:href="#courriel_icone"/>
            </svg>
            <span class="screen-reader-only">courriel</span>
            <span class="courriel__tx">info@librairietraces.ca</span>
        </p>
        <p class="telephone">
            <svg class="icone--joindre" aria-hidden="true">
                <use xlink:href="#telephone_icone"/>
            </svg>
            <span class="screen-reader-only">telephone</span>
            <span class="telephone__tx">1-800-999-8787</span>
        </p>
    </div>

    {{-- Média sociaux --}}
    <div class="sociaux">
        <p class="h3">Suivez-nous</p>
        <div class="sociaux__conteneur">
            <a class="sociaux__lien icone" href="#">
                <svg class="icone" aria-hidden="true">
                    <use xlink:href="#facebook"/>
                </svg>
                <span class="screen-reader-only">Facebook</span>
            </a>
            <a class="sociaux__lien icone" href="#">
                <svg class="icone" aria-hidden="true">
                    <use xlink:href="#instagram"/>
                </svg>
                <span class="screen-reader-only">Instagram</span>
            </a>
            <a class="sociaux__lien icone" href="#">
                <svg class="icone" aria-hidden="true">
                    <use xlink:href="#twitter"/>
                </svg>
                <span class="screen-reader-only">Twitter</span>
            </a>
        </div>
    </div>

    <small class="footer__copyright">Marie-Noëlle Grant  |  Christine Daneau-Pelletier  |  Marie-Pierre Cardinal-Labrie <br>
        Tous droits réservés © TIM 2019 Librairie Traces - Réalisation de produits numériques interactifs III
    </small>
    <small class="footer__copyright footer__copyright_flex">
        <a class="footer__lien bouton--lien" href="#">Conditions d’utilisation</a>
        <a class="footer__lien bouton--lien" href="#">Politique de confidentialité</a>
        <a class="footer__lien bouton--lien" href="#">Politique de retour</a>
        <a class="footer__lien bouton--lien" href="#">Certificat de sécurité</a>
        <a class="footer__lien bouton--lien" href="#">Crédit des icônes</a>
    </small>

</div>

