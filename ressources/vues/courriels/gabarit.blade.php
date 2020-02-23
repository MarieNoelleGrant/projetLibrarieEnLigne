<!DOCTYPE html>
<html lang="FR">
<head>
    <meta charset="utf-8">
    <title>Confirmation de commande | Librairie Traces</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body style="width: 525px; margin: 0 auto">
<header>
    @include('courriels.fragments.entete')
</header>

<main>
    @yield('contenu')
</main>

<footer>
    @include('courriels.fragments.pieddepage')
</footer>
</body>
</html>