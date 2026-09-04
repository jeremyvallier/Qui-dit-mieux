<?php
//Rôle : ce template affiche la page nouvelle enchère du site
//Paramètres :
//      $annonce => annonce sur laquelle l'utilisateur fait une nouvelle enchère
//      $prixActuel => prix courant de l'annonce
require_once "templates/fragments/header.php";
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/styles.css">
    <title>Document</title>
</head>
<body>
    <h1>Nouvelle enchère</h1>
    <h2>
        <?= htmlspecialchars($annonce->get("titre")) ?>
    </h2>
    <h3>
        Prix actuel : <?= htmlspecialchars($prixActuel) ?> euros
    </h3>
    <form class="nouv-enchere" method="post" action="enregistrer_nouv_enchere.php">
        <input type="hidden" name="id" value="<?= $annonce->id() ?>">
        <label>
            Votre enchère :
            <!--la valeur numérique saisie doit être au minimum celle du prix actuel + 1-->
            <input type="number" name="enchere" min="<?= $prixActuel + 1 ?>" required >
            euros
        </label>
        <input type="submit" value="Enchérir">
    </form>
    <a href="preparation_detail.php?id=<?= $annonce->id() ?>">
        Annuler
    </a>
</body>
</html>
