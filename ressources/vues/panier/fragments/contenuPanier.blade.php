@foreach ($refSessionPanier->getItems() as $item)
    <article class="panier__item itemPanier">
        <ul class="panier__sousliste">
            <li class="panier__souslisteItem itemPanier__image"><a href="index.php?controleur=livre&action=fiche&idLivre={{$item->livre->id_livre}}"><img src="{{$item->livre->getSrcAvecISBN()}}" alt="Couverture du livre {{$item->livre->getTitreFormate()}}"/></a></li>
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
                <span>Format @if($item->formatChoisi=='electronique')électronique @else{{$item->formatChoisi}}@endif</span>
                <span>@if($item->formatChoisi=='papier') {{$refSessionPanier->formaterPrix($item->livre->prix_livre)}} @else {{$refSessionPanier->formaterPrix($item->livre->prix_electronique_livre)}} @endif</span>
            </li>

            @if($nomPage == 'Confirmation' || $nomPage == "Validation")
                <li class="panier__souslisteItem itemPanier__quantite">
                    <span>Quantité : {{$item->quantite}}</span>
                </li>
            @else
                <li class="panier__souslisteItem itemPanier__quantite">
                    <form action="index.php?controleur=panier&action=majQuantite&isbn={{$item->livre->isbn_livre}}&sansJs=true&pagePrecedente={{$_GET['pagePrecedente']}}" method="post">
                        <label for="{{$item->livre->isbn_livre}}">Quantité:</label>
                        <select id="{{$item->livre->isbn_livre}}" name="choixQte" class="selectQte">
                            @for($intCpt=1; $intCpt<=10; $intCpt++)
                                <option value="{{$intCpt}}" @if($item->quantite==$intCpt) selected @endif>{{$intCpt}}</option>
                            @endfor
                        </select>
                        <button class="bouton bouton--js bouton--secondaire">Mettre à jour la quantité</button>
                    </form>
                </li>
                <li class="panier__souslisteItem itemPanier__btnRetirer">
                    <form @if($nomPage != 'Validation')action="index.php?controleur=panier&action=supprimerItem&isbn={{$item->livre->isbn_livre}}&pagePrecedente={{$_GET['pagePrecedente']}}@if(isset($_GET['idLivre']))&idLivre={{$_GET['idLivre']}}@endif" @endif method="post">
                        <button class="bouton--secondaire">
                            <svg class="icone" aria-hidden="true">
                                <use xlink:href="#supprimer"/>
                            </svg>
                            <span>Retirer du panier</span>
                        </button>
                    </form>
                </li>
            @endif
            <li class="panier__souslisteItem itemPanier__total">
                <span>Total</span>
                <span class="montantAjax">{{$refSessionPanier->formaterPrix($item->getMontantTotal())}}</span>
            </li>
        </ul>
    </article>
@endforeach

@if($nomPage != 'Validation')
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
                        <option value="standard" @if($refSessionPanier->getMontantSousTotal()>50)class="displayNone" @else class=" " @endif>Standard - {{$refSessionPanier->getMessageModeLivraison('standard')}}</option>
                        <option value="prioritaire" @if($refSessionPanier->getModeLivraison()=='prioritaire')selected @endif>Prioritaire - {{$refSessionPanier->getMessageModeLivraison('prioritaire')}}</option>
                        <option value="gratuit" @if($refSessionPanier->getMontantSousTotal()<50)class="displayNone" @else class=" " selected @endif>Gratuit - {{$refSessionPanier->getMessageModeLivraison('gratuit')}}</option>
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
@endif