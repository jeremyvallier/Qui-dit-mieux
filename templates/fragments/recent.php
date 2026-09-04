<?php
// Rôle : ce fragment affiche une carte résumant une annonce.
// Paramètre :
//      $annonce = objet annonce à afficher
//      $photoPrincipale = objet photo lié à l'objet annonce
?>
<article class="card-recent">
    <div class="photo-recent">
        <?php
        $photo = new photo();
        $photoPrincipale = $photo->photoPrincipale($annonce->id());
        if ($photoPrincipale !== false) {
        ?>
            <img src="<?= htmlspecialchars($photoPrincipale->get("url")) ?>" alt="<?= htmlspecialchars($annonce->get("titre")) ?>">
        <?php
        } else {
        ?>
            <span>
                <?= htmlspecialchars($annonce->get("titre")) ?>
            </span>
        <?php
        }
        ?>
    </div>
    <div class="info-recent">
        <h3 class="titre-recent">
            <?= htmlspecialchars($annonce->get("titre")) ?>
        </h3>
        <p class="prix-recent">
            Prix actuel :
            <?= htmlspecialchars($annonce->prixCourant()) ?> €
        </p>
        <a href="preparation_detail.php?id=<?= $annonce->id() ?>">
            Voir
        </a>
    </div>
</article>