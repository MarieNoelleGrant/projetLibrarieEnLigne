@extends('gabarit')

@section('finirTitle'){{$nomPage}}@endsection
@section('metaDescription')Accueil de la librairie Traces spécialisée dans les livres dédiés à l’histoire. La librairie Traces possède huit succursales à travers le pays et maintient des activités d’édition afin de soutenir la publication des auteurs qui consacrent leurs travaux aux évènements historiques.@endsection
@section('metaKeywords')coups de coeur, actualités litéraires, nouvautés litéraires @endsection
@section('metaAuthor')Christine Daneau-Pelletier @endsection
@section('classeContexte')accueil @endsection

@section('contenu')
    @if($refPanierSession!=""&&count($messageConfirmationPanier)>0)
        @include('panier.fragments.modalePanier')
    @endif

    <h1 class="screen-reader-only">Librairie Traces</h1>
    <p class="msgConnexion">@if(isset($_SESSION['$aDeconnecte']))<svg><use xlink:href="#info"/></svg>{{$_SESSION['msgConnexion']}}@endif</p>

    {{----------------------------------}}
    {{------------ BANNIÈRE ------------}}
    {{----------------------------------}}
    <div class="banniere">
        <img class="banniere_image" src="liaisons/images/banniere.svg" alt="logo et nom de la Librairie Traces">
    </div>
    {{-----------------------------------------}}
    {{------------ COUPS DE COEURS ------------}}
    {{-----------------------------------------}}
    <section class="coupCoeur">
        <div class="coupCoeur__couleurH2">
            <div class="coupCoeur__nomSection conteneur">
                <h2 class="h2Accueil">Coups de coeur</h2>
                <span class="coupCoeur__lienVoirTous lienVoirTous__conteneur">
                    <a class="lienVoirTous__a bouton--lien" href="index.php?controleur=livre&action=index&special=coups-coeur">Voir tous les coups de coeur</a>
                </span>
            </div>
        </div>
        <div class="mobileOnly">
            <div class="coupCoeur__grid conteneur">
                @foreach($arrLivresCoupCoeurMobile as $livre)
                    <div class="coupCoeur__grid_item" id="{{$livre->id_livre}}">
                        <img class="coupCoeur__image" src="{{$livre->getSrcAvecISBN()}}" alt="couverture du livre : {{$livre->getTitreFormate()}}">
                        <h3 class="p coupCoeur__titre">
                            <span class="screen-reader-only">Titre : </span>{{$livre->getTitreFormate()}}
                            <span class="js_desactive"><br><a href="index.php?controleur=livre&action=fiche&idLivre={{$livre->id_livre}}&pagePrecedente=Accueil" class="bouton--lien">Voir la fiche</a></span>
                        </h3>
                    </div>
                @endforeach
                <div class="coupCoeur__grid_item coupCoeur__grid_item--desc">
                    <p class="coupCoeur__grid_selectionnez">Sélectionnez un coup de coeur !</p>
                </div>
            </div>
        </div>
        <div class="tableOnly">
            <div class="coupCoeur__grid conteneur">
                @foreach($arrLivresCoupCoeurTable as $livre)
                    <div class="coupCoeur__grid_item focusable" id="{{$livre->id_livre}}" tabindex="0">
                        <img class="coupCoeur__image" src="{{$livre->getSrcAvecISBN()}}" alt="couverture du livre : {{$livre->getTitreFormate()}}">
                        <h3 class="p coupCoeur__titre">
                            <span class="screen-reader-only">Titre : </span>{{$livre->getTitreFormate()}}
                            <span class="js_desactive"><br><a href="index.php?controleur=livre&action=fiche&idLivre={{$livre->id_livre}}&pagePrecedente=Accueil" class="bouton--lien">Voir la fiche</a></span>
                        </h3>
                    </div>
                @endforeach
                <div class="coupCoeur__grid_item coupCoeur__grid_item--desc">
                    <p class="coupCoeur__grid_selectionnez">Sélectionnez un coup de coeur !</p>
                </div>
            </div>
        </div>
    </section>

    {{-----------------------------------}}
    {{------------ NOUVEAUTÉS ------------}}
    {{-----------------------------------}}
    <section class="nouveautes">
        <div class="nouveautes__couleurH2">
            <div class="nouveautes__nomSection conteneur">
                <h2 class="h2Accueil">Nouveautés</h2>
                <span class="nouveautes__lienVoirTous lienVoirTous__conteneur"><a class="lienVoirTous__a bouton--lien" href="index.php?controleur=livre&action=index&special=nouveautes">Voir toutes les nouveautés</a></span>
            </div>
        </div>
        <div class="livres__liste carousel">

            @foreach ($arrLivres as $livre)
                @if($livre->getParution()->etat_parution == 'Nouveauté')
                <article class="livres__item livre">

                    <a class="nouveautes__lien" href="index.php?controleur=livre&action=fiche&idLivre={{$livre->id_livre}}&pagePrecedente=Accueil">
                        <img class="livre__image" src="{{$livre->getSrcAvecISBN()}}" alt="couverture du livre : {{$livre->getTitreFormate()}}">
                    </a>

                    <ul class="livre__desc">
                        <li class="livre__desc_item livre__titre">
                            <a class="nouveautes__lien" href="index.php?controleur=livre&action=fiche&idLivre={{$livre->id_livre}}">
                                <span class="screen-reader-only">Titre : </span><h3 class="p">{{$livre->getTitreFormate()}}</h3>
                            </a>
                        </li>

                        @foreach ($livre->getAuteurs() as $auteur)
                            <li class="livre__desc_item livre__auteur"><span class="screen-reader-only">auteur : </span>{{$auteur->getPrenomNom()}}</li>
                        @endforeach

                        <li class="livre__desc_item">
                            <ul class="livre__desc_itemFlex">
                                <li class="livre__prix"><span class="screen-reader-only">Prix : </span>{{$livre->getPrixFormate($livre->prix_livre)}}</li>
                                <li class="livre__panier">
                                    <form action="index.php?controleur=panier&action=ajouterItem&isbn={{$livre->isbn_livre}}&nomPage={{$nomPage}}" method="post">
                                        <button class="livre__bouton">
                                            <span class="icone">
                                                <span class="screen-reader-only">Ajouter le livre {{$livre->titre_livre}} au panier</span>
                                                <span><svg class="icone" aria-hidden="true"><use xlink:href="#panier--couleur-plus"/></svg></span>
                                            </span>
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </li>

                    </ul>

                </article>
                @endif
            @endforeach

        </div>

        {{-- Boutons de navigation --}}
{{--        <div class="tableOnly">--}}
{{--            <button class="nouveautes__btn--precedent"></button>--}}
{{--            <button class="nouveautes__btn--suivant"></button>--}}
{{--        </div>--}}
    </section>

    {{------------------------------------}}
    {{------------ ACTUALITÉS ------------}}
    {{------------------------------------}}
    <section class="actualites">
        <div class="actualites__couleurH2">
            <div class="actualites__nomSection conteneur">
                <h2 class="h2Accueil">Actualités</h2>
                <span class="actualites__lienVoirTous lienVoirTous__conteneur"><a class="lienVoirTous__a bouton--lien" href="#">Voir toutes les actualités</a></span>
            </div>
        </div>
        <div class="actualites__flex conteneur">
        @foreach($arrActualites as $actualite)
        <article class="actualites__article">
            <div class="actualites__img"><img src="liaisons/images/actualiteAuteur_{{$actualite->id_actualite}}.jpg" alt="Image ce l'article : {{$actualite->titre_actualite}}"></div>
            <div class="actualites__contenu">
                <h3 class="actualites__titre">{{$actualite->titre_actualite}}</h3>
                <time class="actualites__date" datetime="{{$actualite->date_actualite}}">{{$actualite->date_actualite}}</time>
                <p class="actualites__texte">{{$actualite->getMiniTexte()}}</p>
                <footer class="actualites__footer">
                    @foreach ($actualite->getAuteurs() as $auteur)
                        <p  class="actualites__auteur">Texte de {{$auteur->getPrenomNom()}}</p>
                    @endforeach
                    <a class="actualites__suiteA bouton--lien" href="#">Lire la suite<span class="screen-reader-only"> de cette actualté : {{$actualite->titre_actualite}}</span></a>
                </footer>
            </div>
        </article>
        @endforeach
        </div>
    </section>

@endsection

