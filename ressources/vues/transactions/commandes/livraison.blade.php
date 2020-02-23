@extends('gabarit')

@section('finirTitle'){{$nomPage}}@endsection
@section('metaDescription') Informations de livraisons pour un achat sur le site de Traces @endsection
@section('metaKeywords') livraison transaction @endsection
@section('metaAuthor') Marie-Pierre Cardinal-Labrie @endsection
@section('classeContexte')livraison @endsection

@section('contenu')
    <div class="conteneur">
        <div class="formulaire">
            @include('transactions.fragments.etapesProgression')
            <h1>Livraison</h1>
            <section>
                <form action="index.php?controleur=livraison&action=valider&pagePrecedente=livraison" method="post">
                    <!-- Nom & Prénom -->
                    <!--=======================================-->
                    <fieldset class="groupeCouleur groupeNom">
                        <legend class="h3">Votre nom</legend>
                        <div class="nom ctnForm">
                            <div class="nom__block">
                                <label for="nom">Nom</label>
                                <input type="text"
                                       name="nom"
                                       id="nom"
                                       pattern="[A-ZÄ-Ÿ][A-Za-zÀ-ÿ' \.-]+"
                                       @if($erreurs != null && $erreurs["nom"]["message"] != null) class="erreur" @endif @if($erreurs != null)
                                       value="{{$erreurs["nom"]["valeur"]}}" @endif
                                       required />
                            </div>
                            <p aria-live="assertive" class="msgErreur">
                                @if($erreurs != null)@if($erreurs["nom"]["message"] != null)<span class="icone icone--erreur"></span>{{$erreurs["nom"]["message"]}}@else<span class="icone icone--crochet"></span>@endif @endif
                            </p>
                        </div>
                        <div class="prenom ctnForm">
                            <div class="prennom__block">
                                <label for="prenom">Prenom</label>
                                <input type="text"
                                       name="prenom"
                                       id="prenom"
                                       pattern="[A-ZÄ-Ÿ][A-Za-zÀ-ÿ' \.-]+"
                                       class="@if($erreurs != null && $erreurs["prenom"]["message"] != null) erreur @endif "
                                       value="@if($erreurs != null){{$erreurs["prenom"]["valeur"]}}@endif"
                                       required />
                            </div>
                            <p aria-live="assertive" class="msgErreur">
                                @if($erreurs != null)@if($erreurs["prenom"]["message"] != null)<span class="icone icone--erreur"></span>{{$erreurs["prenom"]["message"]}}@else<span class="icone icone--crochet"></span>@endif @endif
                            </p>
                        </div>
                    </fieldset>

                    <!-- Adresse -->
                    <!--=======================================-->
                    <fieldset>
                        <div class="groupeCouleur">
                            <legend class="h3 adresse__legend">Adresse de livraison</legend>
                            <div class="adresse ctnForm">
                                <div class="adresse__block">
                                    <label for="adresse">Adresse</label>
                                    <input type="text" id="adresse" name="adresse" pattern="[0-9]+[a-zA-ZÀ-ÿ0-9 ,-]+" @if($erreurs != null && $erreurs["adresse"]["message"] != null) class="erreur" @endif @if($erreurs != null) value="{{$erreurs["adresse"]["valeur"]}}" @endif required>
                                </div>
                                <p aria-live="assertive" class="msgErreur">@if($erreurs != null)@if($erreurs["adresse"]["message"] != null)<span class="icone icone--erreur"></span>{{$erreurs["adresse"]["message"]}}@else<span class="icone icone--crochet"></span>@endif @endif</p>
                            </div>
                            <div class="ville ctnForm">
                                <div class="ville__block">
                                    <label for="ville">Ville</label>
                                    <input type="text" id="ville" name="ville" pattern="[A-Za-zÀ-ÿ' -]+" @if($erreurs != null && $erreurs["ville"]["message"] != null) class="erreur" @endif @if($erreurs != null) value="{{$erreurs["ville"]["valeur"]}}" @endif required>
                                </div>
                                <p aria-live="assertive" class="msgErreur">@if($erreurs != null)@if($erreurs["ville"]["message"] != null)<span class="icone icone--erreur"></span>{{$erreurs["ville"]["message"]}}@else<span class="icone icone--crochet"></span> @endif @endif</p>
                            </div>
                            <div class="provinces ctnForm">
                                <div class="provinces__block">
                                    <label for="provinces">Province</label>
                                    <select name="provinces" id="provinces" @if($erreurs != null && $erreurs["provinces"]["message"] != null) class="erreur" @endif required>
                                        <option value="0">Sélectionnez une province</option>
                                        <option value="AB" @if($erreurs != null && $erreurs["provinces"]["valeur"] == "AB") selected @endif >Alberta</option>
                                        <option value="BC" @if($erreurs != null && $erreurs["provinces"]["valeur"] == "BC") selected @endif >Colombie-Britanique</option>
                                        <option value="MB" @if($erreurs != null && $erreurs["provinces"]["valeur"] == "MB") selected @endif >Manitoba</option>
                                        <option value="NB" @if($erreurs != null && $erreurs["provinces"]["valeur"] == "NB") selected @endif >Nouveau-Brunswick</option>
                                        <option value="NL" @if($erreurs != null && $erreurs["provinces"]["valeur"] == "NL") selected @endif >Terre-Neuve-et-Labrador</option>
                                        <option value="NS" @if($erreurs != null && $erreurs["provinces"]["valeur"] == "NS") selected @endif >Nouvelle-Écosse</option>
                                        <option value="ON" @if($erreurs != null && $erreurs["provinces"]["valeur"] == "ON") selected @endif >Ontario</option>
                                        <option value="PE" @if($erreurs != null && $erreurs["provinces"]["valeur"] == "PE") selected @endif >Île-du-Prince-Édouard</option>
                                        <option value="QC" @if($erreurs != null && $erreurs["provinces"]["valeur"] == "QC") selected @endif >Québec</option>
                                        <option value="SK" @if($erreurs != null && $erreurs["provinces"]["valeur"] == "SK") selected @endif >Saskatchewan</option>
                                        <option value="NT" @if($erreurs != null && $erreurs["provinces"]["valeur"] == "NT") selected @endif >Territoires du Nord-Ouest</option>
                                        <option value="NU" @if($erreurs != null && $erreurs["provinces"]["valeur"] == "NU") selected @endif >Nunavut</option>
                                        <option value="YT" @if($erreurs != null && $erreurs["provinces"]["valeur"] == "YT") selected @endif >Yukon</option>
                                    </select>
                                </div>
                                <p aria-live="assertive" class="msgErreur">@if($erreurs != null)@if($erreurs["provinces"]["message"] != null)<span class="icone icone--erreur"></span>{{$erreurs["provinces"]["message"]}}@else<span class="icone icone--crochet"></span>@endif @endif</p>
                            </div>
                            <div class="codePostal ctnForm">
                                <div class="codePostal__block">
                                    <label for="postal">Code postal</label>
                                    <span class="consigne">Ex: H0H 0H0</span>
                                    <input type="text"
                                           id="postal"
                                           name="postal"
                                           pattern="[A-Za-z][0-9][A-Za-z] [0-9][A-Za-z][0-9]"
                                           class="codePostal__input @if($erreurs != null && $erreurs["postal"]["message"] != null) erreur @endif"
                                           value="@if($erreurs != null) {{$erreurs["postal"]["valeur"]}} @endif"
                                           required>
                                </div>
                                <p aria-live="assertive" class="msgErreur">@if($erreurs != null)@if($erreurs["postal"]["message"] != null)<span class="icone icone--erreur"></span>{{$erreurs["postal"]["message"]}}@else<span class="icone icone--crochet"></span>@endif @endif</p>
                            </div>
                        </div>


                        <div class="groupeCouleur groupCheckbox">
                            <!-- Checkbox optionnels en lien avec l'adresse -->
                            <!--=======================================-->
                            <div class="checkbox">
                                <input type="checkbox" id="defaut" name="defaut">
                                <label for="defaut">Adresse de livraison par défaut</label>
                            </div>
                            <div class="checkbox">
                                <input type="checkbox" id="facturation" name="facturation">
                                <label for="facturation">Utiliser comme adresse de facturation</label>
                            </div>
                        </div>
                    </fieldset>

                    <button type="submit" class="bouton bouton--principal">Livrer à cette adresse</button>
                </form>
            </section>
        </div>
    </div>
@endsection