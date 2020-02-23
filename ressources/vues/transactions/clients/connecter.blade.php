@extends('gabarit')

@section('finirTitle'){{$nomPage}}@endsection
@section('metaDescription') Connexion à la Librairie Traces - Accueil de la librairie Traces spécialisée dans les livres dédiés à l’histoire. La librairie Traces possède huit succursales à travers le pays et maintient des activités d’édition afin de soutenir la publication des auteurs qui consacrent leurs travaux aux évènements historiques. @endsection
@section('metaKeywords') Connexion à la Librairie Traces @endsection
@section('metaAuthor')Christine Daneau-Pelletier @endsection
@section('classeContexte')connexion @if(isset($_SESSION['$aDeconnecte']) == null)@if(isset($_SESSION['msgConnexion'])) aMsgConnexion @endif @endif @endsection

@section('contenu')
    @if (isset($_GET['pagePrecedente']))
        @if($_GET['pagePrecedente'] != 'Livraison')
            <div class="filAriane conteneur">
                <ul class="filAriane__liste">
                    <li class="filAriane__listeItem filAriane__2eNiveau"><a href="{{$filAriane}}">Retour</a></li> <!-- mettre une variable du nom de la page -->
                </ul>
            </div>
        @endif
    @endif
    <div class="conteneur">
        <div class="formulaire">
            <h1>@if($etatConnexion == false) Connectez-vous @else Bonjour {{$client->getPrenomNom()}} @endif</h1>

            @if(isset($_SESSION['$aDeconnecte']) == false)@if(isset($_SESSION['msgConnexion']))<p class="msgConnexion"><svg><use xlink:href="#info"/></svg>{{$_SESSION['msgConnexion']}}</p>@endif @endif

            @if($etatConnexion == false)
                <section>
                    <form action="index.php?controleur=client&action=connexion&pagePrecedente={{$_GET['pagePrecedente']}}@if(isset($_GET['idLivre']))&idLivre={{$_GET['idLivre']}}@endif" method="POST" class="groupeCouleur">
                        <!--=======================================-->
                        <!-- Courriel -->
                        <!--=======================================-->
                        <div class="ctnForm">
                            <div>
                                <label for="courriel">Courriel</label>
                                <input type="email"
                                       name="courriel"
                                       id="courriel"
                                       title="Adresse courriel avec un @ et une extension"
                                       class="@if($messages == 'on' && $arrValidationCourriel['estValide'] === false) erreur @endif"
                                       pattern="[a-zA-Z0-9_]+(.[a-zA-Z0-9_]+)*[@][a-zA-Z0-9_]+(.[a-zA-Z0-9_]+)*.[a-zA-Z]{2,4}"
                                       value="@if($messages == 'on'){{$arrValidationCourriel['valeur']}}@endif"
                                       required >
                            </div>
                            <p aria-live="assertive" class="msgErreur">@if($messages == 'on')@if($arrValidationCourriel['message'] != '')<span class="icone icone--erreur"></span>{{$arrValidationCourriel['message']}}@elseif($arrValidationCourriel['estValide'] === true)<span class="icone icone--crochet"></span>@endif @endif</p>
                        </div>


                        <!--=======================================-->
                        <!-- Mot de passe -->
                        <!--=======================================-->
                        <div class="mdp ctnForm">
                            <div class="mdp__input">
                                <label for="mdp">Mot de passe</label>
                                <input type="password"
                                       name="mdp"
                                       id="mdp"
                                       title="Au moins 8 caratères dont un chiffre, une minuscule et une majuscule"
                                       class="@if($messages == 'on' && $arrValidationMDP['estValide'] === false) erreur @endif"
                                       pattern="(?=.*[a-zà-ÿ])(?=.*[A-ZÀ-Ÿ])(?=.*[0-9]).{8,18}"
                                       minlength="8"
                                       value="@if($messages == 'on'){{$arrValidationMDP['valeur']}}@endif"
                                       required/>

                                <!-- Bouton toggle pour voir le mot de passe -->
                                <div class="toggleMDP">
                                    <input class="screen-reader-only toggleMDP__input" type="checkbox" id="toggleMDP">
                                    <label class="toggleMDP__label" for="toggleMDP">
                                        <span class="toggleMDP__label_tx">Afficher<span class="screen-reader-only"> le mot de passe</span></span>
                                        <span class="icone icone--oeil icone--oeil-ouvert" aria-hidden="true"></span>
                                    </label>
                                </div>
                            </div>
                            @if($messages == 'on' && $messageConnexion != '')
                                <p aria-live="assertive" class="msgErreur messageConnexion">@if($messages == 'on' && $messageConnexion != '')<span class="icone icone--erreur"></span>{{$messageConnexion}}@endif</p>
                            @else
                                <p aria-live="assertive" class="msgErreur">@if($messages == 'on')@if($arrValidationCourriel['message'] != '')<span class="icone icone--erreur"></span>{{$arrValidationMDP['message']}}@elseif($arrValidationCourriel['estValide'] === true)<span class="icone icone--crochet"></span>@endif @endif</p>
                            @endif
                        </div>

                        <a href="#" class="bouton--lien">Mot de passe oublié?</a>
                        <button type="submit" class="bouton bouton--principal" formnovalidate>Se connecter</button>
                    </form>
                </section>

                <div>
                    <!--=======================================-->
                    <!-- Lien vers créer un compte -->
                    <!--=======================================-->
                    <section class="groupeCouleur pasCompte">
                        <p>Vous n'avez pas de compte ?</p>
                        <p><a href="index.php?controleur=client&action=ficheCreer&pagePrecedente={{$_GET['pagePrecedente']}}@if(isset($_GET['idLivre']))&idLivre={{$_GET['idLivre']}}@endif" class="bouton--lien">Se créer un compte</a></p>
                    </section>

                    <!--=======================================-->
                    <!-- Option sans compte (fake) -->
                    <!--=======================================-->
                    <section class="achatSansCompte">
                        <input type="button" value="Acheter sans créer de compte" class="bouton bouton--principal">
                    </section>
                </div>

            @else
                <div class="connexionTrue">
                    <p class="groupeCouleur">Vous êtes connecté à la Librairie Traces.</p>
                    <div class="flex">
                        <div class="retour">
                            <a href="index.php?controleur=livre&action=index" class="bouton--lien">Continuer à magasiner</a>
                        </div>
                        <div class="deconnexion">
                            <a href="index.php?controleur=client&action=deconnexion&pagePrecedente={{$_GET['pagePrecedente']}}@if(isset($_GET['idLivre']))&idLivre={{$_GET['idLivre']}}@endif" class="bouton bouton--principal">Me déconnecter</a>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

@endsection