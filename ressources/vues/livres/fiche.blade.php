@extends('gabarit')

@section('finirTitle'){{$refUnLivre->getTitreFormate()}} - {{$filAriane[1]['titre']}}@endsection
@section('metaDescription')Fiche avec toutes les informations sur le livre {{$refUnLivre->titre_livre}}@endsection
@section('metaKeywords'){{$refUnLivre->mots_cles_livre}}@endsection
@section('metaAuthor')Marie-Noëlle Grant @endsection
@section('classeContexte')fiche @endsection

@section('contenu')
    @if($refPanierSession!=""&&count($messageConfirmationPanier)>0)
        @include('panier.fragments.modalePanier')
    @endif

    @include('livres.fragments.filariane')
    <div class="conteneur">
        <section class="fiche__infosPrincipales @if($refUnLivre->est_coup_de_coeur_livre == 1) fiche__infosPrincipales_coupDeCoeur @endif infosPrincipales">
            <div class="infosPrincipales__titreCategorie">
                <h1 class="infosPrincipales__titre">{{$refUnLivre->getTitreFormate()}}</h1>
                @if($refUnLivre->sous_titre_livre!="")
                    <p class="infosPrincipales__sousTitre">{{$refUnLivre->sous_titre_livre}}</p>
                @endif
                <ul class="infosPrincipales__categories categories">
                    @if($refUnLivre->est_coup_de_coeur_livre == 1)<li class="infosPrincipales__coupCoeur"><span class="infosPrincipales__coupCoeur_icone icone icone--coeur"></span><span class="infosPrincipales__coupCoeur_texte">Coup de coeur</span></li>@endif
                    @foreach($arrCategories as $categorie)
                        <li class="categories__item">{{$categorie->nom_fr_categorie}}</li>
                    @endforeach
                </ul>
            </div>
            <div class="conteneur__flex">
                <div class="infosPrincipales__image">
                    <img src="{{$srcImageLivre}}" @if($srcImageLivre=="liaisons/images/placeholder.jpg")class="infosPrincipales__image_placeholder" @endif alt="Couverture du livre {{$refUnLivre->getTitreFormate()}}"/>
                </div>
                <div class="infosPrincipales__auteursAvis">
                    <p class="infosPrincipales__auteurs">par
                        <?php $intCptAuteurs=0; ?>
                        @foreach ($arrAuteurs as $auteur)
                            @if(count($arrAuteurs)==1 || $intCptAuteurs==0)
                                <?php $intCptAuteurs++; ?>
                                <span class="h2">{{$auteur->getPrenomNom()}}</span>
                            @else
                                <span class="h2">, {{$auteur->getPrenomNom()}}</span>
                            @endif
                        @endforeach
                    </p>
                    <div class="infosPrincipales__avis accrocheAvis">
                        <div class="avis__etoiles accrocheAvis__etoiles">
                            <svg class="icone icone__etoile-pleine" aria-hidden="true">
                                <use xlink:href="#etoile--pleine"/>
                            </svg>
                            <svg class="icone icone__etoile-pleine" aria-hidden="true">
                                <use xlink:href="#etoile--pleine"/>
                            </svg>
                            <svg class="icone icone__etoile-pleine" aria-hidden="true">
                                <use xlink:href="#etoile--pleine"/>
                            </svg>
                            <svg class="icone icone__etoile-pleine" aria-hidden="true">
                                <use xlink:href="#etoile--pleine"/>
                            </svg>
                            <svg class="icone icone__etoile-vide" aria-hidden="true">
                                <use xlink:href="#etoile--vide"/>
                            </svg>
                        </div>
                        <span class="accrocheAvis__ancre bouton bouton--lien"><a href="#sectionAvis">Voir tous les avis</a></span>
                    </div>
                </div>
            </div>
            <div class="infosPrincipales__ajoutPanier ajoutPanier">
                <div class="conteneurPanier">
                    <form action="index.php?controleur=panier&action=ajouterItem&isbn={{$refUnLivre->isbn_livre}}&nomPage=Fiche" method="post">
                        <div class="ajoutPanier__format">
                            <input id="btnRadio__format_papier" type="radio" name="formatLivre" value="papier" class="ajoutPanier__format_radio" checked>
                            <label class="ajoutPanier__controle" for="btnRadio__format_papier" tabindex="0">
                                <span class="ajoutPanier__prix">{{$refUnLivre->getPrixFormate($refUnLivre->prix_livre)}}</span>
                                <span class="ajoutPanier__nomFormat">Papier</span>
                            </label>
                        </div>
                        <div class="ajoutPanier__format">
                            <input id="btnRadio__format_elecro" type="radio" name="formatLivre" value="electronique" class="ajoutPanier__format_radio">
                            <label class="ajoutPanier__controle" for="btnRadio__format_elecro" tabindex="0">
                                <span class="ajoutPanier__prix">@if($refUnLivre->prix_electronique_livre!=0) {{$refUnLivre->getPrixFormate($refUnLivre->prix_electronique_livre)}} @else {{$refUnLivre->getPrixFormate($refUnLivre->getPrixVersionElectro())}} @endif</span>
                                <span class="ajoutPanier__nomFormat">Électronique</span>
                            </label>
                        </div>
                        <div class="ajoutPanier__zoneClic focusable">
                            <button class="bouton bouton--principal bouton__ajoutPanier">
                                <span class="icone icone--panier-blancPlus"></span>
                                <span class="bouton--principal_texte">Ajouter au panier</span>
                            </button>
                        </div>
                        <p class="displayNone ajoutPanier__msgChoixFormat"><span class="icone icone--info"></span><span>Veuillez choisir un format avant de l'ajouter au panier!</span></p>
                    </form>
                    @if($messageConfirmationPanier!="")
{{--                        <div class="messageConfirmationPanier">{{$messageConfirmationPanier}}</div>--}}
                    @endif
                </div>
            </div>
        </section>
    </div>

    <section class="fiche__infosSecondaires infosSecondaires conteneur">
        <ul class="infosSecondaires__liste @if($arrHonneurs==NULL&&$arrMentions==NULL&&$refUnLivre->id_collection==NULL)infosSecondaires__liste_descSeule @endif">
            <li class="infosSecondaires__listeItem informations">
                <div class="infosSecondaires__onglet infosSecondaires__onglet--ouvert conteneur__flex">
                    <h2 class="h3 infosSecondaires__onglet_titre">Informations</h2><span class="icone icone--chevron icone--chevron-fermer mobileOnly"><span class="screen-reader-only">Fermer l'onglet</span></span>
                </div>
                <ul class="infosSecondaires__sousListe">
                    <li class="infosSecondaires__sousListeItem">
                        <h3 class="infosSecondaires__sousListeItem_titre informations__titre h4">Résumé :</h3>
                        <p class="informations__texte tableOnly">{{$refUnLivre->description_livre}}</p>
                        <p class="informations__texte mobileOnly">{{$refUnLivre->getMiniDesc()}}</p>
                        <p class="mobileOnly">
                            <span class="bouton bouton--lien enLirePlus mobileOnly"><span class="icone icone--plus"></span><span class="bouton--lien_texte">En lire plus</span></span>
                        </p>
                    </li>
                    <li class="infosSecondaires__sousListeItem">
                        <h3 class="infosSecondaires__sousListeItem_titre informations__titre h4">Nombre de pages :</h3>
                        <span class="informations__texte">{{$refUnLivre->nbre_pages_livre}} pages</span>
                    </li>
                    <li class="infosSecondaires__sousListeItem">
                        <h3 class="infosSecondaires__sousListeItem_titre informations__titre h4">ISBN :</h3>
                        {{--*** À générer dynamiquement selon les formats--}}
                        <span class="informations__texte">{{$refUnLivre->isbn_livre}}</span>
                    </li>
                    <li class="infosSecondaires__sousListeItem">
                        <h3 class="infosSecondaires__sousListeItem_titre informations__titre h4">Édition :</h3>
                        @foreach($arrEditeurs as $editeur)
                        <span class="informations__texte">{{$editeur->nom_editeur}}<span class="infosSecondaires__sousListeItem_url"><a class="infosSecondaires__sousListeItem_lien" href="{{$editeur->url_editeur}}">(consulter leur site!)</a></span></span>
                        @endforeach
                    </li>
                </ul>
            </li>
            @if($arrHonneurs!=NULL)
            <li class="infosSecondaires__listeItem prix">
                <div class="infosSecondaires__onglet infosSecondaires__onglet--ouvert conteneur__flex">
                    <h2 class="h3 infosSecondaires__onglet_titre">Prix remportés</h2><span class="icone icone--chevron icone--chevron-fermer mobileOnly"><span class="screen-reader-only">Fermer l'onglet</span></span>
                </div>
                <ul class="infosSecondaires__sousListe">
                    @foreach($arrHonneurs as $honneur)
                    <li class="infosSecondaires__sousListeItem prix__infos">
                        <span class="icone prix__icone"><svg class="icone" aria-hidden="true"><use xlink:href="#prix"/></svg></span>
                        <h3 class="h4 prix__titre">{{$honneur->nom_honneur}}</h3>
                        <span class="prix__description">{{$honneur->description_honneur}}</span>
                    </li>
                    @endforeach
                </ul>
            </li>
            @endif
            @if($arrMentions!=NULL)
            <li class="infosSecondaires__listeItem mentions">
                <div class="infosSecondaires__onglet infosSecondaires__onglet--ouvert conteneur__flex">
                    <h2 class="h3 infosSecondaires__onglet_titre">Mentions dans les médias</h2><span class="icone icone--chevron icone--chevron-fermer mobileOnly"><span class="screen-reader-only">Fermer l'onglet</span></span>
                </div>
                <ul class="infosSecondaires__sousListe">
                    {{--*** À générer dynamiquement selon les mentions ***--}}
                    @foreach($arrMentions as $mention)
                    <li class="infosSecondaires__sousListeItem mentions__mention">
                        <blockquote cite="">
                            <p class="mentions__citation">{{$mention->description_recension}}</p>
                            <footer class="mentions__auteur">
                                <span class="mentions__auteurNom">{{$mention->nom_journaliste_recension}}, <cite>{{$mention->nom_media_recension}}</cite></span>
                                <span class="mentions__auteurDate">{{$mention->formaterDate()}}</span>
                            </footer>
                        </blockquote>
                    </li>
                    @endforeach
                </ul>
            </li>
            @endif
            @if($refUnLivre->id_collection!=NULL)
                <li class="infosSecondaires__listeItem collection">
                    <div class="infosSecondaires__onglet infosSecondaires__onglet--ouvert conteneur__flex">
                        <h2 class="h3 infosSecondaires__onglet_titre">Collection</h2><span class="icone icone--chevron icone--chevron-fermer mobileOnly"><span class="screen-reader-only">Fermer l'onglet</span></span>
                    </div>
                    <ul class="infosSecondaires__sousListe">
                        <li class="infosSecondaires__sousListeItem">
                                <p class="collection__nom">{{$refUnLivre->getCollection()->nom_collection}}</p>
                                <p class="collection__description">{{$refUnLivre->getCollection()->description_collection}}</p>
                        </li>
                    </ul>
                </li>
            @endif
        </ul>
    </section>
    <section id="sectionAvis" class="fiche__avisLecteurs avisLecteurs conteneur">
        <h2 class="h3 avisLecteurs__titre"><span class="avisLecteurs__titre_txt">Avis d'autres lecteurs</span></h2>
        <div class="avisLecteurs__liste">
            <article class="avisLecteurs__listeItem">
                <header>
                    <span class="screen-reader-only">Côté cinq étoiles sur cinq selon cet utilisateur</span>
                    <div class="avis__etoiles avisLecteurs__etoiles">
                        <svg class="icone icone__etoile-pleine" aria-hidden="true">
                            <use xlink:href="#etoile--pleine"/>
                        </svg>
                        <svg class="icone icone__etoile-pleine" aria-hidden="true">
                            <use xlink:href="#etoile--pleine"/>
                        </svg>
                        <svg class="icone icone__etoile-pleine" aria-hidden="true">
                            <use xlink:href="#etoile--pleine"/>
                        </svg>
                        <svg class="icone icone__etoile-pleine" aria-hidden="true">
                            <use xlink:href="#etoile--pleine"/>
                        </svg>
                        <svg class="icone icone__etoile-pleine" aria-hidden="true">
                            <use xlink:href="#etoile--pleine"/>
                        </svg>
                    </div>
                    <span class="h4 avisLecteurs__nomDate">Sasuke Uchiha, 12/05/2019</span>
                </header>
                <span class="avisLecteurs__avis">“J’ai adoré ce livre! Très informatif, et très bien présenté. Un classique a avoir dans sa bibliothèque d’enseignant.”</span>
            </article>
            <article class="avisLecteurs__listeItem">
                <header>
                    <span class="screen-reader-only">Côté quatre étoiles sur cinq selon cet utilisateur</span>
                    <div class="avis__etoiles avisLecteurs__etoiles">
                        <svg class="icone icone__etoile-pleine" aria-hidden="true">
                            <use xlink:href="#etoile--pleine"/>
                        </svg>
                        <svg class="icone icone__etoile-pleine" aria-hidden="true">
                            <use xlink:href="#etoile--pleine"/>
                        </svg>
                        <svg class="icone icone__etoile-pleine" aria-hidden="true">
                            <use xlink:href="#etoile--pleine"/>
                        </svg>
                        <svg class="icone icone__etoile-pleine" aria-hidden="true">
                            <use xlink:href="#etoile--pleine"/>
                        </svg>
                        <svg class="icone icone__etoile-vide" aria-hidden="true">
                            <use xlink:href="#etoile--vide"/>
                        </svg>
                    </div>
                    <span class="h4 avisLecteurs__nomDate">Naruto Uzumaki, 08/10/2018</span>
                </header>
                <span class="avisLecteurs__avis">“Très bon livre. Un peu long par contre.”</span>
            </article>
            <article class="avisLecteurs__listeItem">
                <header>
                    <span class="screen-reader-only">Côté quatre étoiles sur cinq selon cet utilisateur</span>
                    <div class="avis__etoiles avisLecteurs__etoiles">
                        <svg class="icone icone__etoile-pleine" aria-hidden="true">
                            <use xlink:href="#etoile--pleine"/>
                        </svg>
                        <svg class="icone icone__etoile-pleine" aria-hidden="true">
                            <use xlink:href="#etoile--pleine"/>
                        </svg>
                        <svg class="icone icone__etoile-pleine" aria-hidden="true">
                            <use xlink:href="#etoile--pleine"/>
                        </svg>
                        <svg class="icone icone__etoile-pleine" aria-hidden="true">
                            <use xlink:href="#etoile--pleine"/>
                        </svg>
                        <svg class="icone icone__etoile-vide" aria-hidden="true">
                            <use xlink:href="#etoile--vide"/>
                        </svg>
                    </div>
                    <span class="h4 avisLecteurs__nomDate">Sakura Haruno, 30/09/2018</span>
                </header>
                <span class="avisLecteurs__avis"></span>
            </article>
        </div>
        <div class="avisLecteurs__boutonsLiens">
            <p class="bouton bouton--lien ajouterUnAvis"><span class="icone icone--plus"></span><span class="bouton--lien_texte">Ajouter un avis</span></p>
            <p class="bouton bouton--lien voirAvis"><a href="#sectionAvis">Voir tous les avis</a></p>
        </div>
    </section>
@endsection