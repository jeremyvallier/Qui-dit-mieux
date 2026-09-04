<?php
// Rôle : ce fragment de template affiche une carte
//        représentant une annonce dans les résultats.
// Paramètre :
//      $annonce = objet annonce à afficher
//      $photoPrincipale = objet photo lié à l'objet annonce
// Commentaire :
//      La photo principale est affichée si elle existe.
//      Sinon, le titre de l'annonce est affiché à sa place.

// Récupération de la photo principale
$photo = new photo();
$photoPrincipale = $photo->photoPrincipale($annonce->id());
// Récupération du prix courant
$prixActuel = $annonce->prixCourant();
?>
<article class="card-trouvee">
    <div class="photo-trouvee">
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
    <div class="info-trouvee">
        <h3 class="titre-trouvee">
            <?= htmlspecialchars($annonce->get("titre")) ?>
        </h3>
        <p class="prix-trouvee">
            Prix courant :
            <?= htmlspecialchars($prixActuel) ?> €
        </p>
        <p class="fin-trouvee">
            Fin de l'enchère :
            <?= htmlspecialchars($annonce->get("date_fin")) ?>
        </p>
        <a href="preparation_detail.php?id=<?= $annonce->id() ?>">
            Voir l'annonce
        </a>
    </div>
</article>