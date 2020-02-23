{{------------------------------------}}
{{------------ NAVIGATION ------------}}
{{------------------------------------}}

{{-- Navigation secondaire (metanavigation) TABLE--}}
<div class="menuSecondaire tableOnly">
    <ul class="menuSecondaire__liste conteneur">
        @if($etatConnexion == true)
        <li class="menuSecondaire__liste_item ">
            <a href="index.php?controleur=client&action=deconnexion" class="menu__lien">
                <span class="bouton--lien">Déconnexion</span>
                <svg class="icone iconeDeconnexion" aria-hidden="true">
                    <use xlink:href="#deconnexion"/>
                </svg>
            </a>
        </li>
        @endif
        <li class="menuSecondaire__liste_item">
            <a href="index.php?controleur=client&action=ficheConnexion&pagePrecedente={{$nomPage}}@if(isset($_GET['idLivre']))&idLivre={{$_GET['idLivre']}}@endif" class="menu__lien">
                <span class="bouton--lien">@if($etatConnexion == true) Mon compte @else Connexion @endif</span>
                <svg class="icone iconeUser" aria-hidden="true">
                    <use xlink:href="#user"/>
                </svg>
            </a>
        </li>
        <li class="menuSecondaire__liste_item">
            <a href="index.php?controleur=panier&action=index" class="menu__lien">
                <span class="bouton--lien">English</span>
                <svg class="icone iconeLangue" aria-hidden="true">
                    <use xlink:href="#langue"/>
                </svg>
            </a>
        </li>
        <li class="menuSecondaire__liste_item panier">
            <a href="index.php?controleur=panier&action=fiche&pagePrecedente={{$nomPage}}@if(isset($_GET['idLivre']))&idLivre={{$_GET['idLivre']}}@endif" class="menu__lien">
                <span class="bouton--lien @if(isset($_SESSION['panier'])&&count($_SESSION['panier']->getItems())>0)panierPlein @endif">Panier</span>
                <svg class="icone iconePanier" aria-hidden="true">
                    <use xlink:href="#panier--couleur"/>
                </svg>
                @if(isset($_SESSION['panier'])&&count($_SESSION['panier']->getItems())>0)<span class="panier__nbItems">{{$_SESSION['panier']->getNombreTotalItemsDifferents()}}</span>@endif
            </a>
        </li>
    </ul>
</div>


{{-------------------------------------------}}
{{------------ NAVIGATION MOBILE ------------}}
{{-------------------------------------------}}
<div class="mobileHeader mobileOnly">
    {{-- mini grid --}}
    <div class="navMobileHeader">

        {{-- Logo selon la table ou le mobile --}}
        <div class="logo__conteneur logo conteneur">
            @if ($nomPage != 'Accueil')
                <a href="index.php?controleur=site&action=accueil" class="logo__lien">
                    <img class="logo__img" src="liaisons/images/logo--mobile.svg" alt="logo de la Librairie Traces">
                </a>
            @endif
        </div>

        {{-- Accès rapide au compte --}}
        <div class="compte mobileOnly">
            <a href="index.php?controleur=client&action=ficheConnexion&pagePrecedente={{$nomPage}}@if(isset($_GET['idLivre']))&idLivre={{$_GET['idLivre']}}@endif" class="icone icone--user navLien">
                <span class="screen-reader-only">@if($etatConnexion == true) Mon compte @else Connexion @endif</span>
            </a>
        </div>

        {{-- Accès rapide au panier --}}
        <div class="panier mobileOnly">
            <a href="index.php?controleur=panier&action=fiche&pagePrecedente={{$nomPage}}" class="icone icone--panier-couleur navLien">
                @if(isset($_SESSION['panier'])&&count($_SESSION['panier']->getItems())>0)<span class="panier__nbItems">{{$_SESSION['panier']->getNombreTotalItemsDifferents()}}</span>@endif
                <span class="screen-reader-only">Panier</span>
            </a>
        </div>
    </div>
    {{-- Navigation principale --}}
    <nav class="menu conteneur mobileOnly">
        <ul class="menu__principal menu__liste">
            <li class="menu__liste_item">
                <a href="index.php?controleur=livre&action=index" class="menu__lien"><span class="icone icone--livre"></span>Livres</a>
            </li>
            <li class="menu__liste_item">
                <a href="#" class="menu__lien"><span class="icone icone--meilleur"></span>Meilleurs vendeurs</a>
            </li>
            <li class="menu__liste_item">
                <a href="index.php?controleur=site&action=apropos" class="menu__lien"><span class="icone icone--decouvrir"></span>Découvrir Traces</a>
            </li>
            <li class="menu__liste_item">
                <a href="#" class="menu__lien"><span class="icone icone--auteurs"></span>Auteurs</a>
            </li>
            <li class="menu__liste_item">
                <a href="#contact" class="menu__lien"><span class="icone icone--contact"></span>Nous Joindre</a>
            </li>
            <li>
                <ul class="menu__secondaire">
                    @if($etatConnexion == true)
                    <li>
                        <a href="index.php?controleur=client&action=deconnexion" class="menu__lien">
                            <span class="">Déconnexion</span>
                            <span class="icone icone--deconnexion"></span>
                        </a>
                    </li>
                    @endif
                    <li>
                        <a href="#" class="menu__lien">
                            <span class="">English</span>
                            <span class="icone icone--langue"></span>
                        </a>
                    </li>
                </ul>
            </li>
        </ul>
    </nav>
</div>



<div class="tableflex conteneur mobileRecherche">
    {{------------------------------}}
    {{------------ LOGO ------------}}
    {{------------------------------}}
    <div class="logo__conteneur logo tableOnly">
        @if ($nomPage != 'Accueil')
            <a href="index.php?controleur=site&action=accueil" class="logo__lien">
                <img class="logo__img" src="liaisons/images/logo--petit.svg" alt="logo de la Librairie Traces">
                <span class="logo__nomSite ">TRACES</span>
            </a>
        @endif
    </div>

    {{-----------------------------------}}
    {{------------ RECHERCHE ------------}}
    {{-----------------------------------}}
    <div class="recherche" role="search">
        <form action="#" method="get" class="recherche__form" role="form">
            <label for="filtres" class="screen-reader-only">Ajouter un filtre</label>
            <select name="filtres" id="filtres" class="recherche__filtres">
                <option value="0">Aucun filtre</option>
                <option value="titre">Filtrer par titre</option>
                <option value="auteur">Filtrer par auteur</option>
                <option value="isbn">Filtrer par ISBN</option>
                <option value="sujet">Filtrer par sujet</option>
            </select>
            <label for="champRecherche" class="screen-reader-only">Entrez un texte à rechercher</label>
            <input type="text" name="champRecherche" id="champRecherche" class="recherche__input">
            <button type="submit" name="btn_rechercher" value="rechercher" class="recherche__bouton">
                <span class="icone icone--loupe" aria-hidden="true"></span>
                <span class="screen-reader-only">Rechercher</span>
            </button>
        </form>
        <ul class="recherche__resultats">
        </ul>
    </div>

</div>

{{------------------------------------------}}
{{------------ NAVIGATION TABLE ------------}}
{{------------------------------------------}}
<nav class="menu conteneur tableOnly">
    <ul class="menu__principal menu__liste">
        <li class="menu__liste_item">
            <a href="index.php?controleur=livre&action=index" class="menu__lien bouton--lien @if ($nomPage != 'Accueil' || $nomPage != 'Accueil')active @endif">Livres</a>
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
        <li class="menu__liste_item">
            <a href="#contact" class="menu__lien bouton--lien">Nous Joindre</a>
        </li>
    </ul>
</nav>