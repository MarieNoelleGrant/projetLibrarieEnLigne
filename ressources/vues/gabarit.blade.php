<!DOCTYPE html>
<html lang="FR">
<head>
    <meta charset="utf-8">
    <title>@yield('finirTitle') | Librairie Traces</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="@yield('metaDescription')">
    <meta name="keywords" content="librairie traces histoire @yield('metaKeywords')">
    <meta name="author" content="@yield('metaAuthor')">
    <link rel="stylesheet" type="text/css" href="../public/liaisons/css/styles.css"/>
    <!-- favicon -->
{{--    <link rel="apple-touch-icon" sizes="180x180" href="assets/images/favicon/apple-touch-icon.png">--}}
{{--    <link rel="icon" type="image/png" sizes="32x32" href="assets/images/favicon/favicon-32x32.png">--}}
{{--    <link rel="icon" type="image/png" sizes="16x16" href="assets/images/favicon/favicon-16x16.png">--}}
{{--    <link rel="manifest" href="assets/images/favicon/site.webmanifest">--}}
{{--    <link rel="mask-icon" href="assets/images/favicon/safari-pinned-tab.svg" color="#5a28d4">--}}
{{--    <meta name="msapplication-TileColor" content="#5a28d4">--}}
{{--    <meta name="theme-color" content="#ffffff">--}}

    <link rel="apple-touch-icon" sizes="180x180" href="../public/liaisons/images/favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="../public/liaisons/images/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="../public/liaisons/images/favicon/favicon-16x16.png">
    <link rel="manifest" href="../public/liaisons/images/favicon/site.webmanifest">
    <link rel="mask-icon" href="../public/liaisons/images/favicon/safari-pinned-tab.svg" color="#ff846e">
    <meta name="msapplication-TileColor" content="#2b5797">
    <meta name="theme-color" content="#ffffff">
</head>
<body>
<a href="#contenu" class="screen-reader-only allerContenu focusable">Aller au contenu</a>
<noscript class="noScript sansJs">
    <svg class="icone" aria-hidden="true">
        <use xlink:href="#info"/>
    </svg>
    Il semblerait que votre javascript soit désactivé. Pour visualiser ce site à son meilleur, veuillez l'activer!</noscript>
<header class="entete entete--complete">
    @if(isset($_GET['pagePrecedente']) && $_GET['pagePrecedente'] == 'Livraison')
        @include('transactions.fragments.entete')
    @elseif(isset($_GET['controleur']))
        @if($_GET['controleur'] == 'livraison' || $_GET['controleur'] == 'facturation' || $_GET['controleur'] == 'validation')
            @include('transactions.fragments.entete')
        @else
            @include('fragments.entete')
        @endif
    @else
        @include('fragments.entete')
    @endif
</header>

<main id="contenu" class="@yield('classeContexte')">
    @yield('contenu')
</main>

<footer class="footer" role="contentinfo">
    @include('fragments.pieddepage')
</footer>
<div class="displayNone">@php echo file_get_contents("../public/liaisons/images/icones/sprite.symbol.svg"); @endphp</div>

<!-- CDN v2018 -->
<script src="https://code.jquery.com/jquery-3.3.1.min.js"
        integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
        crossorigin="anonymous"></script>

<script>window.jQuery || document.write('<script src="../node_modules/jquery/dist/jquery.min.js">\x3C/script>')</script>

<!-- On importe toutes les classes de l'application et on instancie l'application dans app.js. -->
<script src="../node_modules/requirejs/require.js" data-main="liaisons/js/app.js">
</script>

<script src="liaisons/js/interactions.js"></script>
<script src="liaisons/js/_menuMobile.js"></script>
{{--<script src="liaisons/js/carousel.js"></script>--}}

<script>
    $('body').addClass('js');
    $(document).ready(function(){
        accordeonTrisMobile.initialiser();
        menuMobile.configurerNav();
        if ($('main').hasClass('fiche')) {
            accordeonInfos.initialiser();
            boutonsPanier.initialiser();
        }
        // carousel.initialiser();
        // rechercheMobile.cacherToogle();
    });
</script>
</body>
</html>