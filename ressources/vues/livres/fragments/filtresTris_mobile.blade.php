<div class="filtres__couleur mobileOnly">
    <div class="filtres conteneur clearfix">
        <img src="./liaisons/images/bouton--changer-affichage.svg" alt="format liste ou format vignettes" class="filtres__formatPage">
        <div class="filtres__menusDeroulants">
            <div class="categories__accordeon">
                <p class="categories__titre">Catégories</p>
                <button type="button" class="categories__bouton"></button>
            </div>
            <ul class="categories__contenu">
                <li class="categorie @if($categorie == null) categorie--actif @endif"><a href="index.php?controleur=livre&action=index&categories=toutes&idPage=0">Toutes les catégories</a></li>
                @foreach($arrCategories as $nomCategorie)
                    <li class="categorie @if($nomCategorie == $categorie) categorie--actif @endif">
                        <a href="index.php?controleur=livre&action=index&filtre={{$nomCategorie->id_categorie}}&idPage=0">{{$nomCategorie->nom_fr_categorie}}</a>
                    </li>
                @endforeach
            </ul>
            <div class="tris__accordeon">
                <p class="tris__titre">Trier</p>
                <button type="button" class="tris__bouton"></button>
            </div>
            <ul class="tris__contenu">
                <li class="alpha tri @if($tri == "alpha") tri__actif @endif"><a href="index.php?controleur=livre&action=index&tri=alpha" class="tri__alpha tri__lien">A-Z</a></li>
                <li class="alphaDesc tri @if($tri == "alpha_desc") tri__actif @endif"><a href="index.php?controleur=livre&action=index&tri=alpha_desc" class="tri__alphaDesc tri__lien">Z-A</a></li>
                <li class="prix tri @if($tri == "prix") tri__actif @endif"><a href="index.php?controleur=livre&action=index&tri=prix" class="tri__prix tri__lien">$-$$$</a></li>
                <li class="prixDesc tri @if($tri == "prix_desc") tri__actif @endif"><a href="index.php?controleur=livre&action=index&tri=prix_desc" class="tri__prixDesc tri__lien">$$$-$</a></li>
            </ul>
        </div>
        @include('fragments.pagination')
        <form action="index.php?controleur=livre&action=index" method="post">
            <select name="nbParPage" id="nbParPage">
                <option value="8" @if($livresParPage == "8") selected @endif>8 livres par page</option>
                <option value="16" @if($livresParPage == "16") selected @endif>16 livres par page</option>
                <option value="24" @if($livresParPage == "24") selected @endif>24 livres par page</option>
            </select>
            <button type="submit" class="bouton--lien">Changer</button>
        </form>
    </div>
</div>