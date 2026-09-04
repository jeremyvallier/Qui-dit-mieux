<?php
// Rôle : ce fragment de template affiche la liste
//        des résultats de recherche.
// Paramètre :
//      $resultats = liste des annonces correspondant
//                  aux critères de recherche.
// Commentaire :
//      Si aucun résultat n'est trouvé, un message est affiché.
?>
<section class="resultats-recherche">
    <h2>Résultats de recherche</h2>
    <?php if (empty($resultats)) { ?>
        <p>
            Aucune annonce ne correspond à votre recherche.
        </p>
    <?php } else { ?>
        <!--Parcourt tous les résultats de recherche.
            À chaque tour, l'annonce actuelle est stockée dans $annonce.-->
        <?php foreach ($resultats as $annonce) { ?>
            <?php
                require "templates/fragments/trouvee.php";
            ?>
        <?php } ?>
    <?php } ?>
</section>