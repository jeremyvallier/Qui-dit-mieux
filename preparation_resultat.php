<?php
// Rôle : ce contrôleur récupère les critères de recherche
//        et prépare les résultats.
// Paramètres :
//      GET : motCle, categorie, etat, prix, statut
// Retour :
//      page recherche.php avec les résultats

require_once "librairie/initialisation.php";

// Récupération des catégories
$url = "https://api.mywebecom.ovh/play/qdm/categ.php";
// Récupère les données des catégories depuis l'API.
$json = file_get_contents($url);
// Vérifie que les données ont bien été récupérées.
if ($json === false) {
    $categories = [];
} else {
    // Transforme les données JSON en tableau PHP.
    $categories = json_decode($json, true);
    // Vérifie que les catégories sont bien dans un tableau.
    if (!is_array($categories)) {
        $categories = [];
    }
}
// Récupère les critères envoyés dans l'URL.
// Si un critère n'est pas présent, utilise une valeur vide.
$motCle = $_GET["motCle"] ?? "";
$categorie = $_GET["categorie"] ?? "";
$etat = $_GET["etat"] ?? "";
$prix = $_GET["prix"] ?? "";
$statut = $_GET["statut"] ?? "";
// Vérification : au moins un critère doit être renseigné
// Vérifie si tous les critères sont vides
if (
    $motCle === ""
    && $categorie === ""
    && $etat === ""
    && $prix === ""
    && $statut === ""
) {
    // Affiche un message si aucun critère n'a été renseigné.
    $erreur = "Veuillez renseigner au moins un critère de recherche.";
    // Prépare une liste de résultats vide.
    $resultats = [];
    // Réaffiche la page de recherche avec le message d'erreur.
    require "templates/pages/recherche.php";
    exit;
}
// Création d'un objet annonce
// Crée un objet annonce pour effectuer la recherche
$annonce = new annonce();
// Met à jour le statut des annonces dont la date de fin est dépassée.
$annonce->mettreAJourStatuts();
// Recherche des annonces correspondant aux critères renseignés
$resultats = $annonce->annonceTrouvee(
    $motCle,
    $categorie,
    $etat,
    $prix,
    $statut
);
// Affichage de la page de recherche avec les résultats trouvés
require "templates/pages/recherche.php";
?>