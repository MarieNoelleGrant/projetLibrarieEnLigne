@extends('gabarit')

@section('finirTitle'){{$nomPage}}@endsection
@section('metaDescription') création d'un nouveau compte sur le site de Traces @endsection
@section('metaKeywords') inscription @endsection
@section('metaAuthor')Christine Daneau-Pelletier @endsection
@section('classeContexte')creer @endsection

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
            <h1 class="">Créer un compte</h1>
            <section>
                <form action="index.php?controleur=client&action=creer&pagePrecedente={{$_GET['pagePrecedente']}}@if(isset($_GET['idLivre']))&idLivre={{$_GET['idLivre']}}@endif" method="POST" class="groupeCouleur">

                    <!-- Nom & Prénom -->
                    <!--=======================================-->
                    <div class="prenom ctnForm" aria-labelledby="nom">
                        <div class="prenom__block">
                            <label for="prenom">Prénom</label>
                            <input type="text"
                                   name="prenom"
                                   id="prenom"
                                   title="Seulement des lettres, espaces ou appostrophes"
                                   class="@if($messages == 'on' && $arrValidationPrenom['estValide'] === false) erreur @endif"
                                   pattern="[A-ZÀ-Ÿ][a-zA-ZÀ-ÿ( |\-|')]{1,29}"
                                   value="@if($messages == 'on'){{$arrValidationPrenom['valeur']}}@endif"
                                   required/>
                        </div>
                        <p aria-live="assertive" class="msgErreur">@if($messages == 'on')@if($arrValidationCourriel['message'] != '')<span class="icone icone--erreur"></span>{{$arrValidationPrenom['message']}}@else<span class="icone icone--crochet"></span>@endif @endif</p>
                    </div>
                    <div class="nom ctnForm" aria-labelledby="nom">
                        <div class="nom__block">
                            <label for="nom">Nom</label>
                            <input type="text"
                                   name="nom"
                                   id="nom"
                                   title="Seulement des lettres, espaces ou appostrophes"
                                   class="@if($messages == 'on' && $arrValidationNom['estValide'] === false) erreur @endif"
                                   pattern="[A-ZÀ-Ÿ][a-zA-ZÀ-ÿ( |\-|')]{1,49}"
                                   value="@if($messages == 'on'){{$arrValidationNom['valeur']}}@endif"
                                   required
                            />
                        </div>
                        <p aria-live="assertive" class="msgErreur">@if($messages == 'on')@if($arrValidationCourriel['message'] != '')<span class="icone icone--erreur"></span>{{$arrValidationNom['message']}}@else<span class="icone icone--crochet"></span>@endif @endif</p>
                    </div>

                    <!-- Courriel -->
                    <!--=======================================-->
                    <div class="courriel ctnForm" aria-labelledby="courriel">
                        <div class="courriel__block">
                            <label for="courriel">Courriel</label>
                            <input type="email"
                                   name="courriel"
                                   id="courriel"
                                   title="Adresse courriel avec un @ et une extension"
                                   class="@if($messages == 'on' && $arrValidationCourriel['estValide'] === false) erreur @endif"
                                   pattern="[a-zA-Z0-9_]+(.[a-zA-Z0-9_]+)*[@][a-zA-Z0-9_]+(.[a-zA-Z0-9_]+)*.[a-zA-Z]{2,4}"
                                   value="@if($messages == 'on'){{$arrValidationCourriel['valeur']}}@endif"
                                   required/>
                        </div>
                        <p aria-live="assertive" class="msgErreur dispoCourriel">@if($messages == 'on')@if($arrValidationCourriel['message'] != '')<span class="icone icone--erreur"></span>{{$arrValidationCourriel['message']}}@else<span class="icone icone--crochet"></span>@endif @endif</p>
                        <p class=""></p>
                    </div>


                    <!-- Téléphone -->
                    <!--=======================================-->

                    <div class="tel ctnForm" aria-labelledby="tel">
                        <div class="tel__block">
                            <label for="telephone" class="tel__label">Téléphone</label>
                            <span class="consigne">Ex : 4181231234</span>
                            <input type="text"
                                   name="telephone"
                                   id="telephone"
                                   title="10 chiffres sans tiret ou espace"
                                   class="tel__input @if($messages == 'on' && $arrValidationTel['estValide'] === false) erreur @endif"
                                   minlength="10"
                                   maxlength="10"
                                   pattern="[0-9]{10}"
                                   value="@if($messages == 'on'){{$arrValidationTel['valeur']}}@endif"
                                   required/>
                        </div>
                        <p aria-live="assertive" class="msgErreur">@if($messages == 'on')@if($arrValidationCourriel['message'] != '')<span class="icone icone--erreur"></span>{{$arrValidationTel['message']}}@else<span class="icone icone--crochet"></span>@endif @endif</p>
                    </div>


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

                        <p aria-live="assertive" class="msgErreur">@if($messages == 'on')@if($arrValidationCourriel['message'] != '')<span class="icone icone--erreur"></span>{{$arrValidationMDP['message']}}@else<span class="icone icone--crochet"></span>@endif @endif</p>

                        <div class="aideSaisie mdp__aideSaisie">
                            <span class="aideSaisie__titre">Pour renforcer la sécurité de votre mot de passe : </span>
                            <ul class="aideSaisie__liste">
                                <li class="aideSaisie__element aideSaisie__size">saisissez entre 8 et 18 caractères</li>
                                <li class="aideSaisie__element aideSaisie__minus">ajoutez au moins une minuscule</li>
                                <li class="aideSaisie__element aideSaisie__majus">ajoutez au moins une majuscule</li>
                                <li class="aideSaisie__element aideSaisie__num">ajoutez au moins un chiffre</li>
                            </ul>
                        </div>

                    </div>

                    <button type="submit" class="bouton bouton--principal">Créer le compte</button>
                </form>
            </section>


            <section>
                <div class="dejaCompte groupeCouleur">
                    <p>Vous avez déjà un compte?</p>
                    <p><a href="index.php?controleur=client&action=ficheConnexion&pagePrecedente={{$_GET['pagePrecedente']}}@if(isset($_GET['idLivre']))&idLivre={{$_GET['idLivre']}}@endif" class="bouton--lien">Se connecter</a></p>
                </div>
            </section>


            <section class="achatSansCompte">
                <input type="button" class="bouton bouton--principal" value="Acheter sans créer de compte">
            </section>
        </div>
    </div>
@endsection