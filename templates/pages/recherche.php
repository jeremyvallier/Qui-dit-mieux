<?php
// Rôle : ce template affiche la page de recherche du site.
//
// Paramètres :
//      $categories = liste des catégories provenant de l'API
//      $resultats = résultats de la recherche, si une recherche a été effectuée

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Qui Dit Mieux - Recherche</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <?php
    $titrePage = "Recherche";
    require_once "templates/fragments/header.php";
    ?>
    <main>
        <?php
        require_once "templates/fragments/form_recherche.php";
        ?>
        <p>
            <a href="index.php">
                Retour
            </a>
        </p>
        <?php
        // Si une recherche a été effectuée,
        // on affiche les résultats.
        if (isset($resultats)) {
            require_once "templates/fragments/resultat.php";
        }
        ?>
    </main>
</body>
</html>