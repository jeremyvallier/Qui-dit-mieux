<?php
//Rôle : ce contrôleur enregistre la nouvelle enchère effectuée par l'utilisateur connecté
//       et qui n'est pas le créateur de l'annonce
//Paramètre : POST enchere saisie dans le champ de saisie
//            POST id => id de l'annonce à laquelle l'utilisateur fait une enchère
//Retour : page detail.php de l'annonce sur laquelle l'utilisateur a enchéri
//Commentaire : l'utilisateur doit être connecté sinon ce contrôleur affiche la page de connexion
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

$annonce->mettreAJourStatuts();
$annonce = new annonce($idAnnonce);
// Vérifie que l'annonce existe bien.
if (!$annonce->is()) {
    header("Location: index.php");
    exit;
}

// Une annonce terminée ne peut plus recevoir d'enchère
if ($annonce->get("statut") == "terminee") {
    header("Location: preparation_detail.php?id=" . $idAnnonce);
    exit;
}

// L'utilisateur ne peut pas enchérir sur sa propre annonce
if ($annonce->get("vendeur_id") == idConnected()) {
    header("Location: index.php");
    exit;
}

// Récupération du montant de l'enchère
$montant = isset($_POST["enchere"])
    ? (float) $_POST["enchere"]
    : 0;
// Récupération du prix courant
$prixActuel = $annonce->prixCourant();
// L'enchère doit être supérieure au prix courant
if ($montant <= $prixActuel) {

    $erreur = "Votre enchère doit être supérieure au prix actuel.";

    require "templates/pages/nouvelle_enchere.php";
    exit;
}
// Enregistrement de l'enchère
$enchere = new enchere();

$enchere->set("montant", $montant);
$enchere->set("date_enchere", date("Y-m-d H:i:s"));
$enchere->set("utilisateur_id", idConnected());
$enchere->set("annonce_id", $idAnnonce);

$enchere->insert();
// Retour au détail de l'annonce
header("Location: preparation_detail.php?id=" . $idAnnonce);
exit;
?>