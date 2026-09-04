<?php
// Rôle : ce template affiche le tableau de bord de l'utilisateur connecté
// Paramètres :
//      $utilisateur => utilisateur connecté
//      $listeAnnonceEnCours => liste des annonces de l'utilisateur encore en cours d'enchères
//      $listeAnnonceSuivi => liste des annonces suivies ou sur lesquelles l'utilisateur a enchéri
//      $listeEnchereRemportee => liste des annonces remportées par l'utilisateur
$titrePage = "Tableau de bord";
require_once "templates/fragments/header.php";
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/styles.css">
    <title>Qui dit mieux - Tableau de bord</title>
</head>
<body>
    <h2>
        Bonjour <?= htmlspecialchars($utilisateur->get("pseudo")) ?>
    </h2>
    <a href="preparation_compte.php?id=<?= $utilisateur->id() ?>">
        Mon compte
    </a>
    <h2>Tableau de bord</h2>
    <a href="preparation_edition.php">Créer une annonce</a>
    <h3>Mes annonces en cours</h3>
    <?php if (empty($listeAnnonceEnCours)) { ?>
        <p>
            Vous n'avez aucune annonce en cours.
        </p>
    <?php } else { ?>
            <!--pour chaque annonce en cours de l'utilisateur connecté, l'affiche-->
        <?php foreach ($listeAnnonceEnCours as $annonce) { ?>
            <!--Stocke l'identifiant de l'annonce dans l'attribut HTML data-annonce-id.-->
            <div class="annonce-dashboard" data-annonce-id="<?= $annonce->id() ?>" >
                <p>
                    Titre :
                    <?= htmlspecialchars($annonce->get("titre")) ?>
                </p>

                <p>
                    Prix courant :
                    <span id="prix-annonce-<?= $annonce->id() ?>">
                        <?= htmlspecialchars($annonce->prixCourant()) ?>
                    </span>
                    euros
                </p>
                <p id="fin-annonce-<?= $annonce->id() ?>">
                    Fin :
                    <?= htmlspecialchars($annonce->get("date_fin")) ?>
                </p>

                <a href="preparation_detail.php?id=<?= $annonce->id() ?>">
                    Voir
                </a>
            </div>
        <?php } ?>
    <?php } ?>
    <h3>Mes annonces suivies / enchéries</h3>
    <?php if (empty($listeAnnonceSuivi)) { ?>
        <p>
            Vous ne suivez ou n'enchérissez sur aucune annonce en cours.
        </p>
    <?php } else { ?>
        <!--pour chaque annonce suivi/enchérit de utilisateur connecté, l'affiche-->
        <?php foreach ($listeAnnonceSuivi as $annonce) { ?>
            <?php
            //Crée un objet enchere pour utiliser les méthodes liées aux enchères.
            $enchere = new enchere();
            //Récupère la meilleure enchère de l'annonce
            $meilleureEnchere =
                $enchere->meilleureEnchere($annonce->id());
                /*Récupère toutes les enchères faites par l'utilisateur connecté sur cette annonce. */
            $mesEncheres =
                $enchere->listeEncheresUtilisateur(
                    $annonce->id(),
                    idConnected()
                );
            // Par défaut, l'utilisateur n'est pas le meilleur enchérisseur.
            $meilleurEncherisseur = false;
            /*Vérifie qu'il existe une meilleure enchère et que l'utilisateur a au moins une enchère sur cette annonce. */
            if (
                $meilleureEnchere !== false &&
                !empty($mesEncheres)
            ) {
                // Parcourt les enchères faites par l'utilisateur connecté.
                foreach ($mesEncheres as $monEnchere) {
                    /*Compare l'identifiant de chaque enchère de l'utilisateur avec celui de la meilleure enchère */
                    if (
                        $monEnchere->id() ==
                        $meilleureEnchere->id()
                    ) {
                        /*Si les deux enchères ont le même identifiant, l'utilisateur est le meilleur enchérisseur. */
                        $meilleurEncherisseur = true;
                        // Inutile de continuer la boucle.
                        break;
                    }
                }
            }
            ?>
            <div
                class="suivi-dashboard"
                data-annonce-id="<?= $annonce->id() ?>"
            >
                <p>
                    Titre :
                    <?= htmlspecialchars($annonce->get("titre")) ?>
                </p>
                <p>
                    Prix courant :
                    <span id="prix-suivi-<?= $annonce->id() ?>">
                        <?= htmlspecialchars($annonce->prixCourant()) ?>
                    </span>
                    euros
                </p>
                <p id="fin-suivi-<?= $annonce->id() ?>">
                    Fin :
                    <?= htmlspecialchars($annonce->get("date_fin")) ?>
                </p>
                <p id="meilleur-suivi-<?= $annonce->id() ?>">
                    Je suis le meilleur enchérisseur ?
                    <?php if ($meilleurEncherisseur) { ?>
                        <span style="color: green;">OUI</span>
                    <?php } else { ?>
                        <span style="color: red;">NON</span>
                    <?php } ?>
                </p>
                <a href="preparation_detail.php?id=<?= $annonce->id() ?>">
                    Voir
                </a>
            </div>
        <?php } ?>
    <?php } ?>
    <h3>Mes enchères remportées</h3>
    <?php if (empty($listeEnchereRemportee)) { ?>
        <p>
            Vous n'avez remporté aucune enchère.
        </p>
    <?php } else { ?>
        <!--pour chaque enchère remportée de utilisateur connecté, l'affiche-->
        <?php foreach ($listeEnchereRemportee as $annonce) { ?>
            <div>
                <p>
                    Titre :
                    <?= htmlspecialchars($annonce->get("titre")) ?>
                </p>
                <p>
                    Prix courant :
                    <?= htmlspecialchars($annonce->prixCourant()) ?>
                    euros
                </p>
                <a href="preparation_detail.php?id=<?= $annonce->id() ?>">
                    Voir
                </a>
            </div>
        <?php } ?>
    <?php } ?>
    <a href="index.php">
        Retour
    </a>
</body>
</html>


