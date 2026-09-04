<?php
// Rôle : affiche la page historique des enchères.
// Paramètres :
//      $annonce
//      $listeEncheres
require_once "templates/fragments/header.php";
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/styles.css">
    <title>Qui dit mieux - Enchère</title>
</head>
<body>
    <a href="preparation_detail.php?id=<?= $annonce->id() ?>">
        Retour à l'annonce
    </a>
    <h1>
        Historique des enchères :
        <?= htmlspecialchars($annonce->get("titre")) ?>
    </h1>
    <?php if (empty($listeEncheres)) { ?>
        <p>
            Aucune enchère n'a encore été effectuée sur cette annonce.
        </p>
    <?php } else { ?>
        <table>
            <thead>
                <tr>
                    <th>Pseudo</th>
                    <th>Montant</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody id="historique-encheres"
            data-annonce-id="<?= $annonce->id() ?>">
                <?php foreach ($listeEncheres as $enchere) { ?>
                    <?php require "templates/fragments/encherisseur.php"; ?>
                <?php } ?>
            </tbody>
        </table>
    <?php } ?>
</body>
</html>
