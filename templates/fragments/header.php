<?php
// Rôle : ce fragment contient le header de plusieurs pages du site.
// Paramètres :
//      $titrePage = titre de la page affichée.
?>
<header>
    <div class="titre-header">
        <h1>Qui Dit Mieux</h1>
    </div>
    <div class="menu">
        <!-- Titre de la page -->
        <h2><?= htmlspecialchars($titrePage) ?></h2>
        <!-- Liens accessibles à tous -->
        <a href="index.php">
            Accueil
        </a>
        <a href="preparation_recherche.php">
            Recherche
        </a>
        <?php if (!isConnected()) { ?>
            <!-- Utilisateur non connecté -->
            <!--action => se connecté-->
            <a href="preparation_connex_inscrip.php?action=connexion">
                Connexion
            </a>
            <!--action => s'inscrire-->
            <a href="preparation_connex_inscrip.php?action=inscription">
                Inscription
            </a>
        <?php } else { ?>
            <!-- Utilisateur connecté -->
            <a href="preparation_tableau_bord.php">
                Tableau de bord
            </a>
            <!--action => se déconnecté-->
            <a href="index.php?action=deconnexion">
                Déconnexion
            </a>
        <?php } ?>
    </div>
</header>