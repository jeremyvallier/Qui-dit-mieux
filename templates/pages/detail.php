<?php
// Rôle : affiche la page détail d'une annonce.
// Paramètres :
//      $annonce
//      $listePhoto
//      $photoPrincipale
//      $prixActuel
//      $nombreEncheres
//      $vendeur
require_once "templates/fragments/header.php";
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/styles.css">
    <title>Qui dit mieux - Détail</title>
</head>
<body>
    <a href="preparation_recherche.php">
        Retour à la recherche
    </a>
    <div class="entete-detail">
        <h2>
            <?= htmlspecialchars($annonce->get("titre")) ?>
        </h2>
        <div class="photo-principale">
            <?php if ($photoPrincipale !== false) { ?>
                <img
                    src="<?= htmlspecialchars($photoPrincipale->get("url")) ?>"
                    alt="<?= htmlspecialchars($annonce->get("titre")) ?>"
                >
            <?php } else { ?>
                <p>
                    <?= htmlspecialchars($annonce->get("titre")) ?>
                </p>
            <?php } ?>
        </div>
        <div class="photos-detail">
            <?php foreach ($listePhoto as $photo) { ?>
                <?php if ($photo->get("principale") != 1) { ?>
                    <img
                        class="photo-secondaire"
                        src="<?= htmlspecialchars($photo->get("url")) ?>"
                        alt="<?= htmlspecialchars($annonce->get("titre")) ?>"
                    >
                <?php } ?>
            <?php } ?>
        </div>
    </div>
    <div class="info-detail">
        <p>
            Description :
            <?= htmlspecialchars($annonce->get("description")) ?>
        </p>

        <p>
            Catégorie :
            <?= htmlspecialchars($libelleCategorie) ?>
        </p>

        <p>
            État :
            <?= htmlspecialchars($annonce->get("etat")) ?>
        </p>

        <p>
            Vendeur :
            <?= htmlspecialchars($vendeur->get("pseudo")) ?>
        </p>

        <p>
            Prix de départ :
            <?= htmlspecialchars($annonce->get("prix_depart")) ?> €
        </p>

        <p>
            Prix courant :
            <?= htmlspecialchars($prixActuel) ?> €
        </p>

        <p>
            Nombre d'enchères :
            <?= htmlspecialchars($nombreEncheres) ?>
        </p>

        <p>
            Fin de l'enchère :
            <?= htmlspecialchars($annonce->get("date_fin")) ?>
        </p>
        <!--l'utilisateur doit être connecté pour pouvoir enchérir-->
        <?php if (!isConnected()) { ?>
            <a href="preparation_connex_inscrip.php">
                Se connecter pour enchérir
            </a>
                <!--l'utilisateur connecté est le vendeur de l'annonce-->
        <?php } else if (idConnected() == $annonce->get("vendeur_id")) { ?>

            <!--Le vendeur peut modifier son annonce uniquement s'il n'y a encore aucune enchère.-->
            <?php if ($nombreEncheres == 0) { ?>

                <a href="preparation_edition.php?id=<?= $annonce->id() ?>">
                    Modifier
                </a>

            <?php } ?>
                <!--Si l'utilisateur n'est pas le vendeur et que l'enchère est encore en cours-->
        <?php } else if ($annonce->get("statut") == "enCours"){ ?>
            <!-- Permet à l'utilisateur d'accéder au formulaire d'enchère -->
            <a href="preparation_nouv_enchere.php?id=<?= $annonce->id() ?>">
                Enchérir
            </a>
        <?php } ?>
        <!--Affiche le lien vers l'historique uniquement si l'utilisateur a le droit(suii l'enchère et/ou a enchérit) de consulter les enchères.-->
        <?php if ($accesHistorique) { ?>

            <a href="preparation_detail.php?id=<?= $annonce->id() ?>&historique=1">
                Voir l'historique des enchères
            </a>
        <?php } ?>
        <!--Si l'utilisateur est connecté et qu'il n'est pas le vendeur-->
        <?php if (isConnected() && idConnected() != $annonce->get("vendeur_id")) { ?>
            <!--Si l'utilisateur ne suit pas encore cette annonce, affiche le bouton pour commencer à la suivre.-->
            <?php if (!$estSuivi) { ?>
                <!--permet à l'utilisateur connecté de suivre l'enchère-->
                <form method="post" action="enregistrer_suivi.php">
                    <input type="hidden" name="id" value="<?= $annonce->id() ?>">
                    <input type="submit" name="suivre" value="Suivre l'enchère">
                </form>
            <!--Si l'annonce est encore en cours et qu'elle est déjà suivie, indique à l'utilisateur qu'il la suit.-->
            <?php } else if ($annonce->get("statut") == "enCours"){ ?>

                <p>Vous suivez cette enchère.</p>

            <?php } ?>

        <?php } ?>
    </div>
</body>
</html>
