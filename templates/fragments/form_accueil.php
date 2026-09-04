<?php
// Rôle : ce fragment de template contient le formulaire
//        de recherche simple de la page d'accueil.
// Paramètres : néant
// Commentaire : recherche d'une annonce par mot-clé.
?>

<form class="form-accueil" method="get" action="preparation_resultat.php">
    <label for="motCle">
        Rechercher une annonce par mot-clé
    </label>
     <input type="text" id="motCle" name="motCle" required>
    <input type="submit" value="Valider">
</form>