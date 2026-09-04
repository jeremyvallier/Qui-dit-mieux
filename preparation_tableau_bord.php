<?php
//Rôle : ce contrôleur génère la page tableau_de_bord.php de l'utilisateur connecté
//Paramètres : id de l'utilisateur connecté
//Retour : page tableau_de_bord.php
//Commentaire : l'utilisateur doit être connecté sinon ce contrôleur affiche
//              la page de connexion + seul l'utilisateur avec les mêmes données
//              que le tableau de bord peut y accéder

require_once "librairie/initialisation.php";
// Vérifie que l'utilisateur est connecté.
if (!isConnected()) {
    require "templates/pages/form_connexion.php";
    exit;
}
// Récupère l'identifiant de l'utilisateur connecté.
$idUtilisateur = idConnected();
// Charge les informations de l'utilisateur.
$utilisateur = new utilisateur($idUtilisateur);

// Vérifie que l'utilisateur existe.
if (!$utilisateur->is()) {
    require "templates/pages/accueil.php";
    exit;
}
// Crée un objet annonce pour récupérer les annonces du tableau de bord.
$annonce = new annonce();
// Met à jour le statut des annonces dont la date de fin est dépassée.
$annonce->mettreAJourStatuts();
// Récupère les annonces actuellement en cours appartenant à l'utilisateur.
$listeAnnonceEnCours = $annonce->listeAnnonceEnCours($idUtilisateur);
// Récupère les annonces suivies ou sur lesquelles l'utilisateur a enchéri
$listeAnnonceSuivi = $annonce->listeAnnonceSuiviEncheries($idUtilisateur);
// Récupère les annonces remportées par l'utilisateur.
$listeEnchereRemportee = $annonce->listeEncheresRemportees($idUtilisateur);

require "templates/pages/tableau_de_bord.php";
?>