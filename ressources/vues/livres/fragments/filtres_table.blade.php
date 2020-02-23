<div class="categories tableOnly">
    <ul class="categories__liste">
        <li class="categorie @if($categorie == null) categorie--actif @endif">
            <a href="index.php?controleur=livre&action=index&categories=toutes&idPage=0">
                Toutes les catégories
            </a>
        </li>
        @foreach($arrCategories as $nomCategorie)
            <li class="categorie @if($nomCategorie == $categorie) categorie--actif @endif">
                <a href="index.php?controleur=livre&action=index&filtre={{$nomCategorie->id_categorie}}&idPage=0">
                    {{$nomCategorie->nom_fr_categorie}}
                </a>
            </li>
        @endforeach
    </ul>
</div>