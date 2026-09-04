<?php
// Rôle : ce template affiche la page d'accueil du site.
// Paramètres : néant
//paramètre pour le header appelé dans accueil.php
$titrePage = "Accueil";
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>QuiDitMieux</title>

    <link rel="stylesheet" href="css/styles.css">
</head>
<body>

    <?php
    require_once "templates/fragments/header.php";
    ?>
    <main>
        <h2 class="titre-accueil">Achetez / Vendez</h2>

        <?php
        require_once "templates/fragments/form_accueil.php";
        ?>
        <h2>Annonces récentes</h2>
        <?php
        require_once "templates/fragments/annonce_accueil.php";
        ?>
    </main>
</body>
</html>