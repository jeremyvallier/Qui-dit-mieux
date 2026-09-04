<?php
// Rôle : ce fragment de template affiche les trois annonces
//        les plus récemment créées.
// Paramètres :
//        $annonces = liste des annonces récentes
?>

<div class="liste-annonces">
     <!--Parcourt la liste des annonces une par une.
        À chaque tour, l'annonce actuelle est stockée dans $annonce.-->
    <?php foreach ($annonces as $annonce) { ?>
        <?php
        // Affiche une carte pour chaque annonce
        require "templates/fragments/recent.php";
        ?>
    <?php } ?>
</div>