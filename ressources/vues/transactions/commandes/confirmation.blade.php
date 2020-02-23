@extends('gabarit')

@section('finirTitle'){{$nomPage}}@endsection
@section('metaDescription') Confirmation de la transaction @endsection
@section('metaKeywords') confirmation transaction @endsection
@section('metaAuthor') Christine Daneau-Pelletier  @endsection
@section('classeContexte') confirmation @endsection

@section('contenu')
    <div class="conteneur">
        <div class="formulaire">
            <h1>Confirmation</h1>

            {{------------ BRAVO ! ------------}}
            <div class="msgBravo">
                <p class="h2">
                    <svg class="icone msgBravo__icone" aria-hidden="true">
                        <use xlink:href="#crochet"/>
                    </svg>
                    Nous avons bien reçu votre commande.
                </p>
                <p class="msgBravo__tx">Elle vous sera expédiée selon les modalités que vous avez choisies. N’hésitez pas à consulter notre service à la clientèle pour plus d’informations relatives à votre commande ou votre compte.</p>
                <p class="msgBravo__no">Votre numéro de confirmation est le : XXXXXXXXX.</p>
                <p class="msgBravo__courriel">Vous recevrez d’ici quelques minutes une confirmation de votre commande par courriel.</p>
            </div>

            {{------------ RECAP DU PANIER ------------}}
            <div class="sommaire groupeCouleur">
                <h2 class="h3">Sommaire de la commande</h2>
                <div class="panier">
                    @include("panier.fragments.contenuPanier")
                </div>
            </div>

            {{------------ ADRESSE DE LIVRAISON ------------}}
            <div class="flex groupeCouleur">
            <div class="adresseLivraison">
                <h2 class="h3">Adresse de livraison</h2>
                <address>
                    <p>{{$adresseLivraison["prenom"]}} {{$adresseLivraison["nom"]}}</p>
                    <p>{{$adresseLivraison["adresse"]}}</p>
                    <p>{{$adresseLivraison["ville"]}}, {{$adresseLivraison["provinces"]}}</p>
                    <p>{{$adresseLivraison["postal"]}}</p>
                </address>
            </div>

            {{------------ ADRESSE DE FACTURATION ------------}}
            @if($memeAdresse == false)
            <div class="adresseFacturation">
                <h2 class="h3">Adresse de facturation</h2>
                <address class="adresse__contenu">
                    <p>{{$adresseFacturation["nom_complet"]}}</p>
                    <p>{{$adresseFacturation["adresse"]}}</p>
                    <p>{{$adresseFacturation["ville"]}}, {{$adresseFacturation["provinces"]}}</p>
                    <p>{{$adresseFacturation["postal"]}}</p>
                </address>
            </div>
            @else
            <div class="adresseFacturation">
                <h2 class="h3">Adresse de facturation</h2>
                <p class="adresse__contenu">
                    <svg class="icone adresseFacturation__icone" aria-hidden="true">
                        <use xlink:href="#crochet"/>
                    </svg>
                    Utiliser la même adresse pour facturation et la livraison.
                </p>
            </div>
            @endif
            </div>

            {{------------ INFORMATIONS DE PAIEMENT ------------}}
            <div class="paiement groupeCouleur">
                <h2 class="h3">Mode de paiement: carte de crédit</h2>
                <p>{{$adresseFacturation["nom_complet"]}}</p>
                <p>{{$adresseFacturation["typeCarte"]}}</p>
            </div>

            <div class="information groupeCouleur">
                <h2 class="h3">Information</h2>
                <p>{{$client->courriel}}</p>
                <p>{{$client->telephone}}</p>
            </div>

            {{------------ BOUTONS ------------}}
            <div class="flex confirmation__btn">
                <div class="retour">
                    <a href="index.php?controleur=site&action=accueil" class="bouton--lien">Continuer à magasiner</a>
                </div>

                <div class="imprimer">
                    <a href="javascript:window.print()"><button class="bouton bouton--principal">Imprimer un reçu</button></a>
                </div>
            </div>

        </div>
    </div>

@endsection