
<div class="pagination">
    <!-- Si on est pas sur la première page et s'il y a plus d'une page -->
    @if ($numeroPage > 0)
        <a href= "{!! $urlPagination . "&page=" . 0  !!}"><span class="chevron__premier"><span class="screen-reader-only">Première page</span></span></a>
    @else
        <span class="chevron__premier chevron__premier--inactif"><span class="screen-reader-only">Vous êtes déjà à la première page</span></span> <!-- Bouton premier inactif -->
    @endif



    @if ($numeroPage > 0)
        <a href="{!! $urlPagination . "&page=" . (htmlspecialchars($numeroPage) - 1) !!}">
            <span class="chevron__precedent"><span class="screen-reader-only">Page précédente</span></span>
        </a>
    @else
        <span class="chevron__precedent chevron__precedent--inactif"><span class="screen-reader-only">Vous êtes déjà à la première page</span></span><!-- Bouton précédent inactif -->
    @endif



    <!-- Statut de progression: page 9 de 99 -->
    <span class="page">{{"Page " . ($numeroPage + 1) . " de " . ($nombreTotalPages + 1)}}</span>

    &nbsp;

    <!-- Si on est pas sur la dernière page et s'il y a plus d'une page -->
    @if ($numeroPage < $nombreTotalPages)
        <a href="{!! $urlPagination . "&page=" . (htmlspecialchars($numeroPage) + 1)  !!}">
            <span class="chevron__suivant"><span class="screen-reader-only">Page suivante</span></span>
        </a>
    @else
        <span class="chevron__suivant chevron__suivant--inactif"><span class="screen-reader-only">Vous êtes déjà à la dernière page</span></span><!-- Bouton suivant inactif -->
    @endif


    @if ($numeroPage < $nombreTotalPages)
        <a href="{!! $urlPagination . "&page=" . htmlspecialchars($nombreTotalPages) !!}">
            <span class="chevron__dernier"><span class="screen-reader-only">Dernière page</span></span>
        </a>
    @else
        <span class="chevron__dernier chevron__dernier--inactif"><span class="screen-reader-only">Vous êtes déjà à la dernière page</span></span><!-- Bouton dernier inactif -->
    @endif
</div>



