<?php
// Rôle : ce template affiche le formulaire de modification du compte.
// Paramètres :
//        $utilisateur => utilisateur connecté
require_once "templates/fragments/header.php";
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/styles.css">
    <title>Qui dit mieux - Compte</title>
</head>
<body>
    <?php if (isset($erreur)) { ?>
    <p>
        <!--message d'erreur si la complétion du formulaire est incorrecte-->
        <?= htmlspecialchars($erreur) ?>
    </p>
    <?php } ?>
    <form method="post" action="enregistrer_compte.php">
        <input type="hidden" name="id" value="<?= $utilisateur->id() ?>" >
        <p>
            <label for="pseudo">
                Pseudo :
            </label>

            <input type="text" id="pseudo" name="pseudo" value="<?= htmlspecialchars($utilisateur->get("pseudo")) ?>" required >
        </p>
        <p>
            <label for="email">
                Adresse email :
            </label>

            <input type="email"id="email" name="email" value="<?= htmlspecialchars($utilisateur->get("email")) ?>" required >
        </p>
        <p>
            <label for="password">
                Nouveau mot de passe :
            </label>

            <input type="password" id="password" name="password" >
        </p>
        <p>
            <small>
                Laissez ce champ vide si vous ne souhaitez pas modifier votre mot de passe.
            </small>
        </p>
        <input type="submit" value="Enregistrer">
    </form>

    <a href="preparation_tableau_bord.php?id=<?= $utilisateur->id() ?>">
        Annuler
    </a>
</body>
</html>
