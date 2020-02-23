@extends('gabarit')

@section('finirTitle'){{$nomPage}}@endsection
@section('metaDescription') Entrée des informations de facturations pour un achat sur le site Traces @endsection
@section('metaKeywords') facturation credit transaction @endsection
@section('metaAuthor') Marie-Pierre Cardinal-Labrie @endsection
@section('classeContexte') facturation @endsection

@section('contenu')
    <div class="conteneur">
        <div class="formulaire">
            @include('transactions.fragments.etapesProgression')
            <h1>Facturation</h1>
            <section>
                <form action="index.php?controleur=facturation&action=valider&pagePrecedente=facturation" method="post">
                    <h2>Informations de paiement</h2>
                    <fieldset class="typePaiement ctnForm groupeCouleur @if($erreurs != null && $erreurs["typePaiement"]["message"] != null) erreur @endif">
                        <legend class="h3">Sélectionnez un type de paiement</legend>
                        <div class="flex">
                            <div class="typePaiement__block">
                                <input class="typePaiement__input screen-reader-only" type="radio" name="typePaiement" value="paypal" id="paypal" required @if($erreurs["typeCarte"]["valeur"] == "paypal") checked @endif>
                                <label class="typePaiement__label typePaiement__label--paypal" for="paypal"><span class="screen-reader-only">Paypal</span></label>
                            </div>
                            <div class="typePaiement__block">
                                <input class="typePaiement__input screen-reader-only" type="radio" name="typePaiement" value="visa" id="visa" @if($erreurs["typeCarte"]["valeur"] == "visa") checked @endif>
                                <label class="typePaiement__label typePaiement__label--visa" for="visa"><span class="screen-reader-only">Visa</span></label>
                            </div>
                            <div class="typePaiement__block">
                                <input class="typePaiement__input screen-reader-only" type="radio" name="typePaiement" value="masterCard" id="masterCard" @if($erreurs["typeCarte"]["valeur"] == "masterCard") checked @endif>
                                <label class="typePaiement__label typePaiement__label--MC" for="masterCard"><span class="screen-reader-only">Master Card</span></label>
                            </div>
                            <div class="typePaiement__block">
                                <input class="typePaiement__input screen-reader-only" type="radio" name="typePaiement" value="amex" id="amex" @if($erreurs["typeCarte"]["valeur"] == "amex") checked @endif>
                                <label class="typePaiement__label typePaiement__label--amex" for="amex"><span class="screen-reader-only">American Express</span></label>
                            </div>
                        </div>
                        <p aria-live="assertive" class="msgErreur">@if($erreurs != null)@if($erreurs["typeCarte"]["message"] != "")<span class="icone icone--erreur"></span>{{$erreurs["typeCarte"]["message"]}}@endif @endif</p>
                    </fieldset>

                    <fieldset class="infosCarte groupeCouleur">
                        <legend class="h3">Les informations de votre carte</legend>
                        <div class="nomCarte ctnForm">
                            <div class="nomCarte__block">
                                <label for="nom_complet">Nom</label>
                                <input type="text" id="nom_complet" name="nom_complet" pattern="[A-ZÄ-Ÿ][A-Za-zÀ-ÿ \.'-]+" required @if($erreurs != null && $erreurs["nom_complet"]["message"] != null) class="erreur" @endif @if($erreurs != null) value="{{$erreurs["nom_complet"]["valeur"]}}" @endif>
                            </div>
                            <p aria-live="assertive" class="msgErreur">@if($erreurs != null)@if($erreurs["nom_complet"]["message"] != "")<span class="icone icone--erreur"></span>{{$erreurs["nom_complet"]["message"]}}@endif @endif</p>
                        </div>
                        <div class="numeroCarte ctnForm">
                            <div class="numeroCarte__block">
                                <label for="numero_carte">Numéro de la carte</label>
                                <input type="text" id="numero_carte" name="numero_carte" pattern="[0-9]{4} [0-9]{4} [0-9]{4} [0-9]{4}" required @if($erreurs != null && $erreurs["numero_carte"]["message"] != null) class="erreur" @endif @if($erreurs != null) value="{{$erreurs["numero_carte"]["valeur"]}}" @endif>
                            </div>
                            <p aria-live="assertive" class="msgErreur">@if($erreurs != null)@if($erreurs["numero_carte"]["message"] != "")<span class="icone icone--erreur"></span>{{$erreurs["numero_carte"]["message"]}}@endif @endif</p>
                        </div>
                        <div class="codeSecCarte ctnForm">
                            <div class="codeSecCarte__block">
                                <label for="numero_securite">Code de sécurité</label>
                                <input type="text" id="numero_securite" name="numero_securite" pattern="[0-9]{3}" @if($erreurs != null && $erreurs["numero_securite"]["message"] != null) class="erreur" @endif @if($erreurs != null) value="{{$erreurs["numero_securite"]["valeur"]}}" @endif maxlength="3" required>
                            </div>
                            <p aria-live="assertive" class="msgErreur">@if($erreurs != null)@if($erreurs["numero_securite"]["message"] != "")<span class="icone icone--erreur"></span>{{$erreurs["numero_securite"]["message"]}}@endif @endif</p>
                        </div>

                        <fieldset class="expirationCarte">
                            <legend class="p expirationCarte__label ctnForm">Date d'expiration</legend>
                                <span class="consigne">Format : MM AAAA</span>
                                <div class="expirationCarte__flex">
                                    <div class="expirationCarte__mois ctnForm">
                                        <label for="mois_expiration" class="screen-reader-only">Mois d'expiration</label>
                                        <select id="mois_expiration" name="mois_expiration" class="expirationCarte__select @if($erreurs != null && $erreurs["mois_expiration"]["message"] != null) erreur @endif " required>
                                            <option value="">MM</option>
                                            <option value="01" @if($erreurs["mois_expiration"]["valeur"] == "01") selected @endif >01</option>
                                            <option value="02" @if($erreurs["mois_expiration"]["valeur"] == "02") selected @endif >02</option>
                                            <option value="03" @if($erreurs["mois_expiration"]["valeur"] == "03") selected @endif >03</option>
                                            <option value="04" @if($erreurs["mois_expiration"]["valeur"] == "04") selected @endif >04</option>
                                            <option value="05" @if($erreurs["mois_expiration"]["valeur"] == "05") selected @endif >05</option>
                                            <option value="06" @if($erreurs["mois_expiration"]["valeur"] == "06") selected @endif >06</option>
                                            <option value="07" @if($erreurs["mois_expiration"]["valeur"] == "07") selected @endif >07</option>
                                            <option value="08" @if($erreurs["mois_expiration"]["valeur"] == "08") selected @endif >08</option>
                                            <option value="09" @if($erreurs["mois_expiration"]["valeur"] == "09") selected @endif >09</option>
                                            <option value="10" @if($erreurs["mois_expiration"]["valeur"] == "10") selected @endif >10</option>
                                            <option value="11" @if($erreurs["mois_expiration"]["valeur"] == "11") selected @endif >11</option>
                                            <option value="12" @if($erreurs["mois_expiration"]["valeur"] == "12") selected @endif >12</option>
                                        </select>
                                    </div>
                                    <div class="expirationCarte__annee ctnForm">
                                            <label for="annee_expiration" class="screen-reader-only">Année d'expiration</label>
                                            <select id="annee_expiration" name="annee_expiration" class="expirationCarte__select @if($erreurs != null && $erreurs["annee_expiration"]["message"] != null) erreur @endif" required>
                                                <option value="">AAAA</option>
                                                <option value="2019" @if($erreurs["annee_expiration"]["valeur"] == "2019") selected @endif >2019</option>
                                                <option value="2020" @if($erreurs["annee_expiration"]["valeur"] == "2020") selected @endif >2020</option>
                                                <option value="2021" @if($erreurs["annee_expiration"]["valeur"] == "2021") selected @endif >2021</option>
                                                <option value="2022" @if($erreurs["annee_expiration"]["valeur"] == "2022") selected @endif >2022</option>
                                                <option value="2023" @if($erreurs["annee_expiration"]["valeur"] == "2023") selected @endif >2023</option>
                                                <option value="2024" @if($erreurs["annee_expiration"]["valeur"] == "2024") selected @endif >2024</option>
                                                <option value="2025" @if($erreurs["annee_expiration"]["valeur"] == "2025") selected @endif >2025</option>
                                            </select>
                                    </div>
                                </div>
                            <p aria-live="assertive" class="msgErreur dateComplete">
                                @if($erreurs != null)
                                    @if($erreurs["mois_expiration"]["message"] != "" || $erreurs["annee_expiration"]["message"] != "" || isset($erreurs["dateComplete"]["message"]))
                                        <span class="icone icone--erreur"></span>
                                    @else
                                        <span class="icone icone--crochet"></span>
                                    @endif
                                <span class="msgDateComplete">
                                    @if($erreurs["mois_expiration"]["message"] != "")
                                        {{$erreurs["mois_expiration"]["message"]}}<br/>
                                    @endif
                                    @if($erreurs["annee_expiration"]["message"] != "")
                                        {{$erreurs["annee_expiration"]["message"]}}<br/>
                                    @endif
                                    @if(isset($erreurs["dateComplete"]["message"]))
                                        {{$erreurs["dateComplete"]["message"]}}
                                    @endif
                                </span>
                                @endif
                            </p>
                        </fieldset>
                    </fieldset>

                    {{-------------------------------}}
                    {{--Si c'est en mode formulaire--}}
                    {{-------------------------------}}
                    @if($memeAdresse == false)
                        <fieldset class="ctnForm infosAdresseFacturation groupeCouleur">
                            <legend class="h3">Adresse de facturation</legend>
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
                                <p aria-live="assertive" class="msgErreur">@if($erreurs != null)@if($erreurs["ville"]["message"] != null)<span class="icone icone--erreur"></span>{{$erreurs["ville"]["message"]}}@else<span class="icone icone--crochet"></span>@endif @endif</p>
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
                                <p aria-live="assertive" class="msgErreur">@if($erreurs != null)@if($erreurs["provinces"]["message"] != "")<span class="icone icone--erreur"></span>{{$erreurs["provinces"]["message"]}}@else<span class="icone icone--crochet"></span>@endif @endif</p>
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
                                           value="@if($erreurs != null){{$erreurs["postal"]["valeur"]}}@endif"
                                           required>
                                </div>
                                <p aria-live="assertive" class="msgErreur">@if($erreurs != null)@if($erreurs["postal"]["message"] != null)<span class="icone icone--erreur"></span>{{$erreurs["postal"]["message"]}}@else<span class="icone icone--crochet"></span>@endif @endif</p>
                            </div>
                        </fieldset>

                        {{--------------------------}}
                        {{--Si c'est en mode texte--}}
                        {{--------------------------}}
                    @else
                        <fieldset class="ctnForm groupeCouleur adresseTx">
                            <legend class="h3">Adresse de facturation</legend>
                            <p class="adresseTx__label">Utiliser mon adresse de livraison pour l'adresse de facturation</p>
                            <address>
                                <p class="facturation__prenomNom">{{$coordonneesAdresse["prenom"]}} {{$coordonneesAdresse["nom"]}}</p>
                                <p class="facturation__adresse">{{$coordonneesAdresse["adresse"]}}</p>
                                <p class="facturation__villeProvince">{{$coordonneesAdresse["ville"]}}, {{$coordonneesAdresse["provinces"]}}</p>
                                <p class="facturation__postal">{{$coordonneesAdresse["postal"]}}</p>
                            </address>
                            <input type="button" class="bouton--secondaire" value="Modifier">
                        </fieldset>
                    @endif
                    <fieldset class="ctnForm groupeCouleur infosFacturation">
                        <legend class="h3">Informations de contact</legend>
                        <p class="infosFacturation__label">Elles seront utilisées pour confirmer votre commande ou vous joindre en cas de besoin pour le suivi de votre commande</p>
                        <p class="infosFacturation__courriel">{{$client->courriel}}</p>
                        <p class="infosFacturation__tel">{{$client->telephone}}</p>
                        <input type="button" class="bouton--secondaire" value="Modifier">
                    </fieldset>
                    <button class="bouton bouton--principal">Valider les informations de paiement</button>
                </form>
            </section>
        </div>
    </div>
@endsection