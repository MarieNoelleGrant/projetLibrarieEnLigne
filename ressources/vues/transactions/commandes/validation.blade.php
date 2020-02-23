@extends('gabarit')

@section('finirTitle'){{$nomPage}}@endsection
@section('metaDescription') Validation de la transaction @endsection
@section('metaKeywords') validation transaction  @endsection
@section('metaAuthor') Marie-Pierre Cardinal-Labrie  @endsection
@section('classeContexte') validation @endsection

@section('contenu')
    <div class="conteneur">
        <div class="formulaire">
            @include('transactions.fragments.etapesProgression')
            <h1>Validation</h1>

            {{------------ BOUTON SUITE ------------}}
            <form action="index.php?controleur=validation&action=sauvegarder&pagePrecedente=validation" method="post">
                <button type="submit" class="bouton bouton--principal">Passer la commande</button>
            </form>

            {{------------ INFORMATIONS DE LIVRAISON ------------}}
            <div class="groupeCouleur">
                <p>Livraison à {{$livraison["prenom"]}} {{$livraison["nom"]}}</p>
                <p>Date de livraison estimée: {{$dateLivraison}}</p>
            </div>

            {{------------ SOMMAIRE DE LA COMMANDE ------------}}
            <div class="validation__sommaire groupeCouleur">
              <h2 class="h3">Sommaire de la commande</h2>
                <ul class="panier__resumeListe">
                    <li class="panier__resumeListeItem panier__sousTotal">
                        <span>Sous-total</span>
                        <span class="montantAjax">{{$refSessionPanier->formaterPrix($refSessionPanier->getMontantSousTotal())}}</span>
                    </li>
                    <li class="panier__resumeListeItem panier__TPS">
                        <span>TPS(5%)</span>
                        <span class="montantAjax">{{$refSessionPanier->formaterPrix($refSessionPanier->getMontantTPS())}}</span>
                    </li>
                    <li class="panier__resumeListeItem panier__livraison"><span>Frais livraison</span>
                        <span class="montantAjax">{{$refSessionPanier->formaterPrix($refSessionPanier->getMontantFraisLivraison())}}</span>
                    </li>
                    <li class="panier__resumeListeItem panier__totalTotal">
                        <span>Total</span>
                        <span class="montantAjax">{{$refSessionPanier->formaterPrix($refSessionPanier->getMontantTotal())}}</span>
                    </li>
                </ul>
            </div>

            {{------------ ADRESSE DE LIVRAISON ------------}}
            <div class="groupeCouleur">
                <h2 class="h3">Adresse de livraison</h2>
                <address>
                    <p>{{$livraison["prenom"]}} {{$livraison["nom"]}}</p>
                    <p>{{$livraison["adresse"]}}</p>
                    <p>{{$livraison["ville"]}}, {{$livraison["provinces"]}}</p>
                    <p>{{$livraison["postal"]}}</p>
                </address>
            </div>

            <div class="groupeCouleur infosFacturation">
                <h2 >Informations de facturation</h2>
                {{------------ ADRESSE DE FACTURATION ------------}}
                <p>Mode de paiement: {{$facturation["typeCarte"]}}</p>
                <img src="" alt=""><span>XXXX XXXX XXXX {{substr($facturation["numero_carte"], 14)}}</span>
                <p>Expiration: {{$facturation["mois_expiration"]}}/{{$facturation["annee_expiration"]}}</p>

                {{------------ ADRESSE DE LIVRAISON ------------}}
                <h3>Adresse de facturation</h3>
                <address>
                <p>{{$livraison["prenom"]}} {{$livraison["nom"]}}</p>
                @if($memeAdresse == true)
                    <p>{{$livraison["adresse"]}}</p>
                    <p>{{$livraison["ville"]}}, {{$livraison["provinces"]}}</p>
                    <p>{{$livraison["postal"]}}</p>
                @else
                    <p>{{$facturation["adresse"]}}</p>
                    <p>{{$facturation["ville"]}}, {{$facturation["provinces"]}}</p>
                    <p>{{$facturation["postal"]}}</p>
                @endif
                </address>

                {{------------ INFORMATIONS ------------}}
                <h3>Informations personnelles</h3>
                <p>{{$client->courriel}}</p>
                <p>{{$client->telephone}}</p>
            </div>

            {{------------ RECAP DU PANIER ------------}}
            <div class="groupeCouleur panier">
                <h2>Mon panier</h2>
                @include("panier.fragments.contenuPanier")
                <input type="button" class="bouton--secondaire" value="Modifier le panier">
            </div>

            {{------------ BOUTON SUITE ------------}}
            <form action="index.php?controleur=validation&action=sauvegarder&pagePrecedente=validation" method="post">
                <button type="submit" class="bouton bouton--principal">Passer la commande</button>
            </form>

        </div>
    </div>


@endsection