<?php
// Rôle : ce contrôleur enregistre le suivi d'une annonce
//        par l'utilisateur connecté.
// Paramètre :
//      POST id => id de l'annonce à suivre
// Retour :
//      page detail.php de l'annonce
// Commentaire : l'utilisateur doit être connecté.

require_once "librairie/initialisation.php";

if (!isConnected()) {
    require "templates/pages/form_connexion.php";
    exit;
}
//récupère l'id de l'annonce est le convertit en entier s'il existe sinon l'id vaut 0
$idAnnonce = isset($_POST["id"]) ? (int) $_POST["id"] : 0;

if ($idAnnonce <= 0) {
    header("Location: index.php");
    exit;
}

$annonce = new annonce($idAnnonce);
// Vérifie que l'annonce existe bien.
if (!$annonce->is()) {
    header("Location: index.php");
    exit;
}

// L'utilisateur ne peut pas suivre sa propre annonce
if ($annonce->get("vendeur_id") == idConnected()) {
    header("Location: preparation_detail.php?id=" . $idAnnonce);
    exit;
}

// Ajout du suivi
$suivi = new suivi();
$suivi->ajouterSuivi(idConnected(), $idAnnonce);

// Retour au détail de l'annonce
header("Location: preparation_detail.php?id=" . $idAnnonce);
exit;

?>