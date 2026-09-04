<?php
// Rôle : ce template affiche le formulaire d'inscription du site.
// Paramètres : Néant

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/styles.css">
    <title>Qui dit mieux</title>
</head>
<body>
    <h1>
        Créer un compte
    </h1>
    <!--action => se connecté-->
    <a href="preparation_connex_inscrip.php?action=connexion">
        Déjà inscrit ? Se connecter
    </a>
    <!--message d'erreur si la complétion du formulaire est incorrecte-->
    <?php if (isset($erreur)) { ?>
        <p>
            <?= htmlspecialchars($erreur) ?>
        </p>
    <?php } ?>
    <form class="connex_inscrip" method="post" action="validation_connex_inscrip.php">
        <label>
            Pseudo :
            <input type="text" name="pseudo" required>
        </label>
        <label>
            Email :
            <input type="email" name="email" required>
        </label>
        <label>
            Mot de passe :
            <input type="password" name="passwordI" required>
        </label>
        <label>
            Combien font 3 + 4 ?
            <input type="number" name="anti_robot" required>
        </label>
        <input type="submit" value="S'inscrire">
    </form>
    <p>
        <a href="index.php">
            Retour
        </a>
    </p>
</body>
</html>
