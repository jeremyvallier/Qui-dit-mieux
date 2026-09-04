<?php
// Rôle : ce contrôleur génère la page edition.php de l'objet annonce
// Paramètres : Néant pour une création d'annonce
//              id de l'annonce à éditer (modifier ou supprimer)
// Retour : page edition.php
// Commentaire : l'utilisateur doit être connecté sinon ce contrôleur affiche
//               la page de connexion
//               + l'utilisateur doit être le créateur de l'annonce
//               pour la modifier / supprimer

require_once "librairie/initialisation.php";
// Vérifie que l'utilisateur est connecté.
if (!isConnected()) {
    // Affiche la page de connexion si l'utilisateur n'est pas connecté.
    require "templates/pages/form_connexion.php";
    exit;
}

// Récupération des catégories
// Adresse de l'API qui contient les catégories.
$url = "https://api.mywebecom.ovh/play/qdm/categ.php";
// Récupère les données des catégories depuis l'API.
$json = file_get_contents($url);
// Transforme les données JSON en tableau PHP.
$categories = json_decode($json, true);
// Vérifie que les catégories récupérées sont bien dans un tableau.
if (!is_array($categories)) {
    // Utilise un tableau vide si les données sont invalides.
    $categories = [];
}
// Récupère l'identifiant de l'annonce envoyé dans l'URL.
// Si aucun identifiant n'est envoyé, utilise 0.
$idAnnonce = isset($_GET["id"]) ? (int) $_GET["id"] : 0;

// Création d'une nouvelle annonce
// Si aucun identifiant valide n'est fourni, on crée une nouvelle annonce.
if ($idAnnonce <= 0) {
    require "templates/pages/edition.php";
    exit;
}

// Modification ou suppression d'une annonce existante
// Charge l'annonce correspondant à l'identifiant reçu.
$annonce = new annonce($idAnnonce);
// Si l'annonce n'existe pas, retourne à l'accueil.
if (!$annonce->is()) {
    require "templates/pages/accueil.php";
    exit;
}
// Vérifie que l'utilisateur connecté est bien le vendeur de l'annonce.
if ($annonce->get("vendeur_id") != idConnected()) {
    // Si ce n'est pas le vendeur, retourne à l'accueil.
    require "templates/pages/accueil.php";
    exit;
}
// Met à jour le statut si la date de fin est dépassée.
$annonce->mettreAJourStatuts();

// Recharge l'annonce pour avoir son statut à jour.
$annonce = new annonce($idAnnonce);

// Vérifie que l'annonce est encore modifiable.
if (
    $annonce->get("statut") == "terminee"
    || $annonce->nombreEncheres() > 0
) {
    // Si l'annonce est terminée ou possède déjà une enchère,
    // elle ne peut plus être modifiée ou supprimée.
    header("Location: preparation_tableau_bord.php");
    exit;
}

// Récupération des photos de l'annonce
// Crée un objet photo pour récupérer les photos de l'annonce.
$photo = new photo();
// Récupère toutes les photos de l'annonce.
$listePhoto = $photo->listePhotos($idAnnonce);
// Récupère la photo principale de l'annonce.
$photoPrincipale = $photo->photoPrincipale($idAnnonce);

require "templates/pages/edition.php";
?>