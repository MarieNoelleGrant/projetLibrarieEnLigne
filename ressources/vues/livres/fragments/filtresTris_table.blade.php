<div class="filtres tableOnly conteneur clearfix">
    <div class="filtres__format">
        <img src="./liaisons/images/bouton--changer-affichage.svg" alt="format liste ou format vignettes" class="filtres__formatPage">
    </div>
    <div class="filtres__pagination">
        @include('fragments.pagination')
        <form action="index.php?controleur=livre&action=index" method="post">
            <select name="nbParPage" id="nbParPage" class="recherche__filtres">
                <option value="8" @if($livresParPage == "8") selected @endif>8 livres par page</option>
                <option value="16" @if($livresParPage == "16") selected @endif>16 livres par page</option>
                <option value="24" @if($livresParPage == "24") selected @endif>24 livres par page</option>
            </select>
            <button type="submit" class="bouton--lien">Changer</button>
        </form>
    </div>
    <div class="filtres__tris">
        <ul class="tris__contenu">
            <li class="alpha tri @if($tri == "alpha") tri__actif @endif"><a href="index.php?controleur=livre&action=index&tri=alpha" class="tri__alpha tri__lien">A-Z</a></li>
            <li class="alphaDesc tri @if($tri == "alpha_desc") tri__actif @endif"><a href="index.php?controleur=livre&action=index&tri=alpha_desc" class="tri__alphaDesc tri__lien">Z-A</a></li>
            <li class="prix tri @if($tri == "prix") tri__actif @endif"><a href="index.php?controleur=livre&action=index&tri=prix" class="tri__prix tri__lien">$-$$$</a></li>
            <li class="prixDesc tri @if($tri == "prix_desc") tri__actif @endif"><a href="index.php?controleur=livre&action=index&tri=prix_desc" class="tri__prixDesc tri__lien">$$$-$</a></li>
        </ul>
    </div>
</div>