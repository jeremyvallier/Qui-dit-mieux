<?php
//Rôle : ce template affiche le formulaire d'édition d'annonce
//Paramètres : Néant => pour une nouvelle annonce
//             id annonce => pour une modification / suppression d'une annonce existante
//             $listePhoto => liste des photos de l'annonce
//             $photoPrincipale => photo principale de l'annonce
//             $categories => liste des catégories
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/styles.css">
    <title>Qui dit mieux - Edition d'annonce</title>
</head>
<body>
    <?php if (!isset($annonce)) { ?>
        <h1>Nouvelle annonce</h1>
    <?php } else { ?>
        <h1>
            <?= htmlspecialchars($annonce->get("titre")) ?>
        </h1>
    <?php } ?>
    <form method="post" action="enregistrer_edition.php" class="form-edition" enctype="multipart/form-data">
        <?php if (isset($annonce)) { ?>
            <!--si l'annonce existe, on récupère son id-->
            <input type="hidden" name="id" value="<?= $annonce->id() ?>">
        <?php } ?>
        <label>
            Titre :
            <!--si l'annonce existe, on affiche son titre sans le champ est vide-->
            <input type="text" name="titre" value="<?= isset($annonce) ? htmlspecialchars($annonce->get("titre")) : "" ?>" required >
        </label>
        <label>
            Description :
            <!--si l'annonce existe, on affiche sa description sans le champ est vide-->
            <textarea name="description" rows="5" cols="33" required><?=
                isset($annonce)
                    ? htmlspecialchars($annonce->get("description"))
                    : ""
            ?></textarea>
        </label>
        <label>
            Date de fin de l'enchère :
            <!--Champ permettant de choisir la date et l'heure de fin de l'enchère. Si l'annonce existe déjà, sa date de fin est affichée dans le champ. Le format est adapté à l'input datetime-local : YYYY-MM-DDTHH:MM.-->
            <input type="datetime-local" name="date_fin"
                value="<?= isset($annonce) ? date("Y-m-d\TH:i", strtotime($annonce->get("date_fin"))) : "" ?>"
                required>
        </label>
        <label for="categorie-select">
            Catégorie :
            <select name="categorie_id" required>
                <!-- Option affichée par défaut si aucune catégorie n'est sélectionnée -->
                <option value="">Choisir une catégorie</option>
                <!--Parcourt toutes les catégories. $idCategorie = identifiant de la catégorie. $libelleCategorie = nom de la catégorie affiché à l'utilisateur.-->
                <?php foreach ($categories as $idCategorie => $libelleCategorie) { ?>
                    <option value="<?= $idCategorie ?>"
                        <?php
                        /* Si on modifie une annonce, sélectionne automatiquement la catégorie qui est déjà enregistrée pour cette annonce.*/
                        if (isset($annonce) && $annonce->get("categorie_id") == $idCategorie) {
                            echo "selected";
                        }
                        ?>
                    >
                        <?= htmlspecialchars($libelleCategorie) ?>
                    </option>
                <?php } ?>
            </select>
        </label>
        <fieldset>
            <legend>Etat</legend>
            <label for="neuf">
                <input type="radio" id="neuf" name="etat" value="neuf"
                    <?php
                    //si l'annonce existe et que le bouton "neuf" est sélectionne, ce bouton radio est checké
                    if (isset($annonce) && $annonce->get("etat") == "neuf") {
                        echo "checked";
                    }
                    ?>
                >
                Neuf
            </label>
            <label for="tresBonEtat">
                <input type="radio" id="tresBonEtat" name="etat" value="tresBonEtat"
                    <?php
                    if (isset($annonce) && $annonce->get("etat") == "tresBonEtat") {
                        echo "checked";
                    }
                    ?>
                >
                Très bon état
            </label>
            <label for="bonEtat">
                <inputtype="radio" id="bonEtat" name="etat" value="bonEtat"
                    <?php
                    if (isset($annonce) && $annonce->get("etat") == "bonEtat") {
                        echo "checked";
                    }
                    ?>
                >
                Bon état
            </label>
            <label for="etatCorrect">
                <input type="radio"id="etatCorrect" name="etat" value="etatCorrect"
                    <?php
                    if (isset($annonce) && $annonce->get("etat") == "etatCorrect") {
                        echo "checked";
                    }
                    ?> >
                Etat correct
            </label>
        </fieldset>
        <label>
            Prix de départ :
            <input type="number" name="prix" value="<?= isset($annonce) ? htmlspecialchars($annonce->get("prix_depart")) : "" ?>" required >
            euros
        </label>
        <!-- Affiche les photos uniquement lors de la modification d'une annonce.-->
        <?php if (isset($annonce)) { ?>
            <h2>Photos actuelles</h2>
            <!--si une "photoPrincipale" existe, on l'affiche-->
            <?php if ($photoPrincipale) { ?>
                <div>
                    <p>Photo principale :</p>
                    <img src="<?= htmlspecialchars($photoPrincipale->get("url")) ?>" alt="<?= htmlspecialchars($annonce->get("titre")) ?>" >
                </div>
            <?php } ?>
            <!--Vérifie si l'annonce possède des photos.-->
            <?php if (!empty($listePhoto)) { ?>
                <div>
                    <p>Photos secondaires :</p>
                    <!--Parcourt toutes les photos de l'annonce.-->
                    <?php foreach ($listePhoto as $photo) { ?>
                        <?php
                        /*Affiche la photo uniquement si elle n'est pas la photo principale (principale != 1). */
                        if (
                            !$photoPrincipale ||
                            $photo->id() != $photoPrincipale->id()
                        ) {
                        ?>
                            <img src="<?= htmlspecialchars($photo->get("url")) ?>" alt="<?= htmlspecialchars($photo->get("url")) ?>">
                        <?php } ?>
                    <?php } ?>
                </div>
            <?php } ?>
        <?php } ?>
        <h2>Ajouter des photos</h2>
        <label>
            Photo principale :
            <input type="file" name="principale" accept="image/*" >
        </label>
        <label>
            Photos secondaires :
            <input type="file" name="photos[]" accept="image/*" multiple >
        </label>
        <a href="preparation_tableau_bord.php" class="bouton">Annuler</a>
        <?php if (isset($annonce)) { ?>

            <input type="submit" name="supprimer" value="Supprimer" >
        <?php } ?>
        <input type="submit" name="publier" value="Publier l'annonce" >

        <input type="hidden" name="date_creation" value="<?= isset($annonce) ? htmlspecialchars($annonce->get("date_creation")) : date("Y-m-d H:i:s") ?>">
    </form>
</body>
</html>
