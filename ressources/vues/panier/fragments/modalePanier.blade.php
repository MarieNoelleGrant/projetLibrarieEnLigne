<div class="conteneur__tableOnly">
    <div id="modale" class="boiteModale">
        <div class="boiteModale__dialogue">
            <div class="boitModale__fenetre">
                <header class="boiteModale__entete">
                    <h2 class="boiteModale__entete_titre">Ajouté à votre panier</h2>
                    <a href="{{$_SERVER['REQUEST_URI']}}" class="boiteModale__fermer">
                        <div class="boiteModale__entete_icone">
                            <svg class="icone" aria-hidden="true">
                                <use xlink:href="#menuX"/>
                            </svg>
                            <span class="screen-reader-only">Fermer</span>
                        </div>
                    </a>
                </header>
                <div class="boiteModale__contenu">
                    <div class="boiteModale__image">
                        <img src="{{$refUnLivre->getSrcAvecISBN()}}" class="boiteModale__image_img @if($refUnLivre->getSrcAvecISBN()=="liaisons/images/placeholder.jpg")boiteModale__image_placeholder @endif" alt="Couverture du livre {{$refUnLivre->getTitreFormate()}}"/>
                    </div>
                    <div class="boiteModale__contenuTexte">
                        <p class="h3">{{$messageConfirmationPanier['titre_livre']}}</p>
                        <p class="h3">@if($formatLivre=='papier'){{$messageConfirmationPanier['prix_livre']}} @else {{$messageConfirmationPanier['prix_electronique_livre']}} @endif</p>
                        <p>Quantité : {{$messageConfirmationPanier['quantite_livre']}}</p>
                        <p>Format @if($formatLivre=='electronique')électronique @else{{$formatLivre}}@endif</p>
                    </div>
                    <div class="boiteModale__actions">
                        <?php $intCpt=0; ?>
                        <a href="{{$_SERVER['REQUEST_URI']}}" class="bouton__retour">
                            <span class="icone icone--chevron icone--chevron-retour"></span>
                            Continuer </a>
                        <a href="index.php?controleur=panier&action=fiche&pagePrecedente={{$nomPage}}@if(isset($_GET['idLivre']))&idLivre={{$_GET['idLivre']}}@else&idLivre={{$refUnLivre->id_livre}}@endif" class="bouton bouton--lien">Voir mon panier</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
