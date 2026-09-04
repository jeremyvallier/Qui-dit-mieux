<?php
// Rôle : affiche l'historique des enchérisseur d'une enchère.
// Paramètres :
//      $enchere = objet enchere
//      $utilisateur = id de l’enchérisseur
$utilisateur = new utilisateur(
    $enchere->get("utilisateur_id")
);
?>
<tr>
    <td>
        <!--htmlspecialchars transforme son contenu en une
            chaîne de caractères (sécurité contre les injection SQL)-->
        <?= htmlspecialchars($utilisateur->get("pseudo")) ?>
    </td>

    <td>
        <?= htmlspecialchars($enchere->get("montant")) ?> €
    </td>

    <td>
        <?= htmlspecialchars($enchere->get("date_enchere")) ?>
    </td>
</tr>