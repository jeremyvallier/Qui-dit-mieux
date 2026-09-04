<?php
// Rôle : ce contrôleur génère la page d'accueil et vérifie
//        le statut de connexion de l'utilisateur.
//        Déconnecte l'utilisateur s'il a cliqué sur le bouton Déconnexion.
// Paramètres :
//       GET action (au clic d'un a href Deconnexion)
// Retour :
//        page accueil.php
// Initialisation de l'application
require_once "librairie/initialisation.php";

// Déconnexion de l'utilisateur
if (isset($_GET["action"]) && $_GET["action"] == "deconnexion") {

    disconnect();

    header("Location: index.php");
    exit;
}
// Récupération des annonces récentes
$annonce = new annonce();

$annonce->mettreAJourStatuts();
$annonces = $annonce->annonceRecente();

// Affichage de la page d'accueil
require_once "templates/pages/accueil.php";

?>