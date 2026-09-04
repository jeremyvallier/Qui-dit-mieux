<?php
// Rôle : ce fragment de template contient le formulaire
//        de recherche complète de la page recherche.
// Paramètres : néant
// Commentaire : recherche par combinaison de plusieurs critères.
//               Au moins un critère doit être renseigné.
?>
<form class="form-recherche" method="get" action="preparation_resultat.php">
    <!-- Recherche textuelle -->
    <label for="motCle">
        Mot-clé :
    </label>
    <input type="text" id="motCle" name="motCle">
    <!-- Catégorie -->
    <label for="categorie-select">
        Catégorie :
    </label>
    <select name="categorie" id="categorie-select">
        <option value="">
            Choisir une catégorie
        </option>
        <!--Parcourt toutes les catégories.
            $id correspond à l'identifiant de la catégorie.
            $libelle correspond au nom affiché de la catégorie.-->
        <?php foreach ($categories as $id => $libelle) { ?>
            <!-- L'identifiant de la catégorie est envoyé avec le formulaire -->
            <option value="<?= htmlspecialchars($id) ?>">
                <?= htmlspecialchars($libelle) ?>
            </option>
        <?php } ?>
    </select>
    <!-- Etat -->
    <fieldset>
        <legend>État</legend>
        <label for="neuf">
            <input type="radio" id="neuf" name="etat" value="neuf" >
            Neuf
        </label>
        <label for="tresBonEtat">
            <input type="radio"id="tresBonEtat" name="etat" value="tresBonEtat" >
            Très bon état
        </label>
        <label for="bonEtat">
            <input type="radio" id="bonEtat" name="etat" value="bonEtat" >
            Bon état
        </label>
        <label for="etatCorrect">
            <input type="radio"id="etatCorrect" name="etat" value="etatCorrect" >
            État correct
        </label>
    </fieldset>
    <!-- Prix -->
    <label for="prix">
        Prix maximum :
    </label>
    <input type="number" id="prix" name="prix" min="0" step="0.01">
    euros
    <!-- Statut de la vente -->
    <fieldset>
        <legend>Vente</legend>
        <label for="enCours">
            <input type="radio" id="enCours" name="statut" value="enCours">
            Ventes en cours
        </label>
        <label for="terminee">
            <input type="radio" id="terminee" name="statut" value="terminee">
            Ventes terminées
        </label>
    </fieldset>
    <input type="submit" value="Rechercher">
</form>