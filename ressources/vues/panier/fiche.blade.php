@extends('gabarit')

@section('finirTitle')Mon panier @endsection
@section('metaDescription')Panier d'achat de la librairie en ligne Traces @endsection
@section('metaKeywords')panier achat @endsection
@section('metaAuthor')Marie-Noëlle Grant @endsection
@section('classeContexte')panier @endsection

    @section('contenu')
        <div class="filAriane conteneur">
            <ul class="filAriane__liste">
                <li class="filAriane__listeItem filAriane__2eNiveau"><a href="{{$filAriane}}">Retour</a></li>
            </ul>
        </div>
        <section class="conteneur">
            <h1 class="panier__titre">Mon Panier</h1>
            <p class="panier__infoPrix">
                <span class="panier__iconeInfo icone icone--info"></span>
                Tous nos prix sont en dollars canadiens (CAD).
            </p>
            @if($refSessionPanier!=null && $refSessionPanier->getNombreTotalItemsDifferents()!=0)
                <div class="panier__recap">
                    <div class="panier__recapSection panier__recapSection_qte">
                        <h2 class="h3">Quantité totale :</h2>
                        <span>{{$refSessionPanier->getNombreTotalItems()}} @if($refSessionPanier->getNombreTotalItems()>1)items @else item @endif</span>
                    </div>
                    <div class="panier__recapSection panier__recapSection_total">
                        <h2 class="h3">Prix total :</h2>
                        <span>{{$refSessionPanier->formaterPrix($refSessionPanier->getMontantTotal())}} (avec taxes)</span>
                    </div>
                    <a href="index.php?controleur=livraison&action=validerConnexion&pagePrecedente=panier" class="bouton bouton--principal panier__btnPayer"><span class="bouton--principal_texte">Passer ma commande</span></a>
                </div>

                @include ('panier.fragments.contenuPanier')

                <div class="panier__boutons">
                    <a href="index.php?controleur=livraison&action=validerConnexion&pagePrecedente=panier" class="bouton bouton--principal panier__btnPayer"><span class="bouton--principal_texte">Passer ma commande</span></a>
                    @else
                        <p class="panier__vide">Vous n'avez rien encore d'ajouté à votre panier!</p>
                    @endif
                    <a href="index.php?controleur=livre&action=index" class="bouton--lien panier__btnContinuer">Continuer à magasiner</a>
                </div>
        </section>
    @endsection