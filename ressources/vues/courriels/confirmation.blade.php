@extends('courriels.gabarit')

@section('contenu')<h1>Confirmation de la commande</h1>
    <div class="msgBravo">
        <h2 class="h2">
            Nous avons bien reçu votre commande.
        </h2>
        <p class="msgBravo__tx">Elle vous sera expédiée selon les modalités que vous avez choisies. N’hésitez pas à consulter notre service à la clientèle pour plus d’informations relatives à votre commande ou votre compte.</p>
        <p class="msgBravo__no">Votre numéro de confirmation est le : XXXXXXXXX.</p>
        <p class="msgBravo__courriel">Vous recevrez d’ici quelques minutes une confirmation de votre commande par courriel.</p>
    </div>

    <div class="sommaire groupeCouleur" style="background: #fafafa; padding: 5px 20px; margin: 20px 0;">
        <h2 class="h3">Sommaire de la commande</h2>
        <div class="panier">
            @foreach ($refSessionPanier->getItems() as $item)
                <article class="panier__item itemPanier">
                    <ul class="panier__sousliste">
                        <li class="panier__souslisteItem itemPanier__titre h3">{{$item->livre->titre_livre}}</li>
                        <li class="panier__souslisteItem itemPanier__auteurs">par
                            <?php $intCptAuteurs=0;?>
                            @foreach ($item->livre->getAuteurs() as $auteur)
                                @if(count($item->livre->getAuteurs())==1 || $intCptAuteurs==0)
                                    <?php $intCptAuteurs++;?>
                                    {{$auteur->getPrenomNom()}}
                                @else
                                    , {{$auteur->getPrenomNom()}}
                                @endif
                            @endforeach
                        </li>
                        <li class="panier__souslisteItem itemPanier__prix">
                            <span>Format {{$item->formatChoisi}}</span>
                            <span>@if($item->formatChoisi=='papier') {{$refSessionPanier->formaterPrix($item->livre->prix_livre)}} @else {{$refSessionPanier->formaterPrix($item->livre->prix_electronique_livre)}} @endif</span>
                        </li>

                        <li class="panier__souslisteItem itemPanier__quantite">
                            <span>Quantité : {{$item->quantite}}</span>
                        </li>
                        <li class="panier__souslisteItem itemPanier__total">
                            <span>Total</span>
                            <span class="montantAjax">{{$refSessionPanier->formaterPrix($item->getMontantTotal())}}</span>
                        </li>
                    </ul>
                </article>
            @endforeach

            <ul class="panier__resumeListe">
                <li class="panier__resumeListeItem panier__sousTotal">
                    <span>Sous-total</span>
                    <span class="montantAjax">{{$refSessionPanier->formaterPrix($refSessionPanier->getMontantSousTotal())}}</span>
                </li>
                <li class="panier__resumeListeItem panier__TPS">
                    <span>TPS(5%)</span>
                    <span class="montantAjax">{{$refSessionPanier->formaterPrix($refSessionPanier->getMontantTPS())}}</span>
                </li>

                @if($nomPage == 'Confirmation')
                    <li class="panier__resumeListeItem panier__livraison">
                        <span>Frais livraison</span>
                        <span class="montantAjax">{{$refSessionPanier->formaterPrix($refSessionPanier->getMontantFraisLivraison())}}</span>
                    </li>
                @else
                    <li class="panier__resumeListeItem panier__livraison">
                        <div>
                            <form action="index.php?controleur=panier&action=majModeLivraison&sansJs=true&pagePrecedente={{$_GET['pagePrecedente']}}" method="post">
                                <label for="choixLivraison">Frais livraison</label>
                                <select id="choixLivraison" name="choixLivraison" class="choixLivraison">
                                    <option for="choixLivraison" value="standard">Standard - {{$refSessionPanier->getMessageModeLivraison('standard')}}</option>
                                    <option for="choixLivraison" value="prioritaire" @if($refSessionPanier->getModeLivraison()=='prioritaire')selected @endif>Prioritaire - {{$refSessionPanier->getMessageModeLivraison('prioritaire')}}</option>
                                    <option for="choixLivraison" value="gratuit" @if($refSessionPanier->getMontantSousTotal()<50)class="displayNone" @else class=" " selected @endif>Gratuit - {{$refSessionPanier->getMessageModeLivraison('gratuit')}}</option>
                                </select>
                                <button class="bouton bouton--secondaire bouton--js">Mettre à jour le mode de livraison</button>
                            </form>
                        </div>
                        <span class="montantAjax">{{$refSessionPanier->formaterPrix($refSessionPanier->getMontantFraisLivraison())}}</span>
                    </li>
                @endif

                <li class="panier__resumeListeItem panier__totalTotal">
                    <span>Total</span>
                    <span class="montantAjax">{{$refSessionPanier->formaterPrix($refSessionPanier->getMontantTotal())}}</span>
                </li>
            </ul>
        </div>
    </div>

    <div class="flex groupeCouleur">
        <div class="adresseLivraison" style="background: #fafafa; padding: 5px 20px; margin: 20px 0;">
            <h2 class="h3">Adresse de livraison</h2>
            <address>
                <p>{{$adresseLivraison["prenom"]}} {{$adresseLivraison["nom"]}}</p>
                <p>{{$adresseLivraison["adresse"]}}</p>
                <p>{{$adresseLivraison["ville"]}}, {{$adresseLivraison["provinces"]}}</p>
                <p>{{$adresseLivraison["postal"]}}</p>
            </address>
        </div>

        @if($memeAdresse == false)
            <div class="adresseFacturation" style="background: #fafafa; padding: 5px 20px; margin: 20px 0;">
                <h2 class="h3">Adresse de facturation</h2>
                <address class="adresse__contenu">
                    <p>{{$adresseFacturation["nom_complet"]}}</p>
                    <p>{{$adresseFacturation["adresse"]}}</p>
                    <p>{{$adresseFacturation["ville"]}}, {{$adresseFacturation["provinces"]}}</p>
                    <p>{{$adresseFacturation["postal"]}}</p>
                </address>
            </div>
        @else
            <div class="adresseFacturation" style="background: #fafafa; padding: 5px 20px; margin: 20px 0;">
                <h2 class="h3">Adresse de facturation</h2>
                <p class="adresse__contenu">
                    Utiliser la même adresse pour facturation et la livraison.
                </p>
            </div>
        @endif
    </div>

    <div class="paiement groupeCouleur" style="background: #fafafa; padding: 5px 20px; margin: 20px 0;">
        <h2 class="h3">Mode de paiement: carte de crédit</h2>
        <address>
            <p>{{$adresseFacturation["nom_complet"]}}</p>
            <p>{{$adresseFacturation["typeCarte"]}}</p>
        </address>
    </div>

    <div class="information groupeCouleur" style="background: #fafafa; padding: 5px 20px; margin: 20px 0;">
        <h2 class="h3">Information</h2>
        <address>
            <p>{{$client->courriel}}</p>
            <p>{{$client->telephone}}</p>
        </address>
    </div>
@endsection