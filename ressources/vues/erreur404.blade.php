@extends('gabarit')

@section('finirTitle') {{$nomPage}} @endsection
@section('metaDescription') Erreur 404 de la librairie Trace @endsection
@section('metaKeywords') erreur @endsection
@section('metaAuthor') Marie-Pierre Cardinal-Labrie @endsection
@section('classeContexte') pageErreur @endsection

@section('contenu')

    <h1 class="conteneur">Erreur 404 - Page non trouvée</h1>
    <p class="conteneur">Il semblerait que ce lien ne fonctionne pas. Nous vous invitons à retourner à <a class="bouton__lien" href="index.php">l'accueil</a> de notre librairie ou à
        <a href="#" class="bouton__lien">nous contacter</a> si le problème persiste.</p>

@endsection