<?php
// Rôle : ce template affiche le formulaire de connexion du site.
// Paramètres : Néant
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/styles.css">
    <title>Qui dit mieux - connexion</title>
</head>
<body>
    <h1>
    Connexion
    </h1>
    <!--action => s'inscrire-->
    <a href="preparation_connex_inscrip.php?action=inscription">
        Pas de compte ? Créer un compte
    </a>
    <!--message d'erreur si la complétion du formulaire est incorrecte-->
    <?php if (isset($erreur)) { ?>
        <p>
            <?= htmlspecialchars($erreur) ?>
        </p>
    <?php } ?>
    <form class="connex_inscrip" method="post" action="validation_connex_inscrip.php">
        <label>
            Identifiant :
            <input type="text" name="identifiant" required>
        </label>
        <label>
            Mot de passe :
            <input type="password" name="password" required>
        </label>
        <input type="submit" value="Se connecter">
    </form>
    <p>
        <a href="index.php">
            Retour
        </a>
    </p>
</body>
</html>
