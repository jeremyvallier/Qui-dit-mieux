<?php
//Rôle : ce contrôleur génère la page nouvelle_enchere.php
//Paramètres : id annonce => annonce sur laquelle l'utilisateur connecté enchérir
//              (et dont il n'est pas le créateur)
//Retour : page nouvelle_enchere.php
//Commentaire : l'utilisateur doit être connecté sinon ce contrôleur affiche
//              la page de connexion + l'utilisateur ne doit pas être le créateur de l'annonce
require_once "librairie/initialisation.php";
// Vérifie que l'utilisateur est connecté.
if (!isConnected()) {
    require "templates/pages/form_connexion.php";
    exit;
}
// Récupère l'identifiant de l'annonce envoyé dans l'URL.
// Si aucun identifiant n'est envoyé, utilise 0.
$idAnnonce = isset($_GET["id"]) ? (int) $_GET["id"] : 0;
// Vérifie que l'identifiant de l'annonce est valide.
if ($idAnnonce <= 0) {
    require "templates/pages/accueil.php";
    exit;
}
// Charge l'annonce correspondant à l'identifiant reçu.
$annonce = new annonce($idAnnonce);

// Vérifie que l'annonce existe.
if (!$annonce->is()) {
    require "templates/pages/accueil.php";
    exit;
}
// Vérifie que l'utilisateur connecté n'est pas le vendeur de l'annonce.
if ($annonce->get("vendeur_id") == idConnected()) {
    // Si c'est sa propre annonce, il ne peut pas enchérir.
    require "templates/pages/accueil.php";
    exit;
}
// Met à jour le statut si la date de fin est dépassée.
$annonce->mettreAJourStatuts();

// Recharge l'annonce avec son nouveau statut.
$annonce = new annonce($idAnnonce);

// Vérifie que l'annonce est encore en cours.
if ($annonce->get("statut") == "terminee") {

    // Une annonce terminée ne peut plus recevoir d'enchère.
    require "templates/pages/accueil.php";
    exit;
}
// Récupère le prix actuel de l'annonce.
$prixActuel = $annonce->prixCourant();
// Définit le titre de la page.
$titrePage = "Nouvelle enchere";
require "templates/pages/nouvelle_enchere.php";
?>