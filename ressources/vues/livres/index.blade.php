@extends('gabarit') {{--hérite de gabarit : il a donc tout son html de base --}}

@section('finirTitle')  @if($categorie == null) Tous les livres
                        @elseif($special == "coups-coeur") Coups de coeur
                        @elseif($special == "nouveautes") Nouveautés
                        @else  {{$categorie->nom_fr_categorie}}
                        @endif
@endsection
@section('metaDescription') Liste des livres @endsection
@section('metaKeywords') Annuaire, liste, librairie @endsection
@section('metaAuthor') Marie-Pierre Cardinal-Labrie @endsection
@section('classeContexte') catalogue @endsection

@section('contenu')
    @if($refPanierSession!=""&&count($messageConfirmationPanier)>0)
        @include('panier.fragments.modalePanier')
    @endif

    @include('livres.fragments.filariane')

    @include('livres.fragments.filtresTris_mobile')

    <h1 class="conteneur clearfix"> @if($categorie == null) Tous les livres @elseif($categorie == "Coups de coeur" || $categorie == "Nouveautés") {{$categorie}} @else {{$categorie->nom_fr_categorie}} @endif</h1>

    @include('livres.fragments.filtresTris_table')


    {{--  AFFICHAGE DE LA LISTE DE LIVRES (boucle)--}}
    <section class="conteneur clearfix index__page">
        <div class="livres__liste">
            @foreach ($arrLivres as $livre)
                <article class="livres__item livre">
                    <a class="livre__lien" href="index.php?controleur=livre&action=fiche&idLivre={{$livre->id_livre}}">
                        <img class="livre__image" src="{{$livre->getSrcAvecISBN()}}" alt="Couverture du livre {{$livre->titre_livre}}">
                    </a>
                    <ul class="livre__desc">
                        <li class="livre__desc_item livre__titre">
                            <h2 class="p"><a href="index.php?controleur=livre&action=fiche&idLivre={{$livre->id_livre}}">
                                {{$livre->titre_livre}}
                            </a></h2>
                        </li>
                        <li class="livre__desc_item livre__auteur"><span class="screen-reader-only">Auteurs:</span>
                            @php $intCptAuteurs=0; @endphp
                            @foreach ($livre->getAuteurs() as $auteur)
                                @if(count($livre->getAuteurs())==1 || $intCptAuteurs==0)
                                    @php $intCptAuteurs++; @endphp
                                    {{$auteur->getPrenomNom()}}
                                @else
                                    , {{$auteur->getPrenomNom()}}
                                @endif
                            @endforeach
                        </li>
                        <li class="livre__desc_item">
                            <ul class="livre__desc_itemFlex">
                                <li class="livre__desc_item livre__prix">{{$livre->getPrixFormate($livre->prix_livre)}}</li>
                                <li class="livre__desc_item livre__panier">
                                    @php
                                        $strQuery = "";
                                        if(isset($_GET['filtre'])){$strQuery=$strQuery.'&filtre='.$_GET['filtre'];}
                                        if(isset($_GET['tri'])){$strQuery=$strQuery.'&tri='.$_GET['tri'];}
                                        if(isset($_GET['idPage'])){$strQuery=$strQuery.'&idPage='.$_GET['idPage'];}elseif(isset($_GET['page'])){$strQuery=$strQuery.'&page='.$_GET['page'];}
                                    @endphp
                                    <a href="index.php?controleur=panier&action=ajouterItem&isbn={{$livre->isbn_livre}}&nomPage={{$nomPage}}@php echo $strQuery;@endphp">
                                        <span class="icone icone--panier-couleur-plus">
                                            <span class="screen-reader-only">Ajouter le livre {{$livre->titre_livre}} au panier
                                            </span>
                                        </span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </article>
            @endforeach
        </div>
        @include('livres.fragments.filtres_table')
    </section>
@endsection