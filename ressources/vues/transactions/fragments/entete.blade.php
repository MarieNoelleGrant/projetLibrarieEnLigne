<div class="entete--tunel">
    {{--------------------------------------}}
    {{------------ LOGO - TABLE ------------}}
    {{--------------------------------------}}
    <div class="menuSecondaire tableOnly">
        <div class="conteneur mobileRecherche">
            <div class="logo__conteneur logo tableOnly">
                <a href="index.php?controleur=site&action=accueil" class="logo__lien">
                    <img class="logo__img" src="liaisons/images/logo--petit.svg" alt="logo de la Librairie Traces">
                    <span class="logo__nomSite ">TRACES</span>
                </a>
            </div>
        </div>
    </div>


    {{---------------------------------------}}
    {{------------ LOGO - MOBILE ------------}}
    {{---------------------------------------}}
    <div class="mobileHeader mobileOnly">
        {{-- mini grid --}}
        <div class="navMobileHeader">

            {{-- Logo selon la table ou le mobile --}}
            <div class="logo__conteneur logo conteneur">
                <a href="index.php?controleur=site&action=accueil" class="logo__lien">
                    <img class="logo__img" src="liaisons/images/logo--mobile.svg" alt="logo de la Librairie Traces">
                </a>
            </div>
    {{--        --}}{{-- Accès rapide au compte --}}
    {{--        <div class="compte mobileOnly">--}}
    {{--            <a href="index.php?controleur=client&action=ficheConnexion" class="icone icone--user navLien">--}}
    {{--                <span class="screen-reader-only">@if($etatConnexion == true) Mon compte @else Connexion @endif</span>--}}
    {{--            </a>--}}
    {{--        </div>--}}

    {{--        --}}{{-- Accès rapide au panier --}}
    {{--        <div class="panier mobileOnly">--}}
    {{--            <a href="index.php?controleur=panier&action=fiche&pagePrecedente={{$nomPage}}" class="icone icone--panier-couleur navLien">--}}
    {{--                <span class="screen-reader-only">Panier</span>--}}
    {{--            </a>--}}
    {{--        </div>--}}
        </div>
    </div>
</div>