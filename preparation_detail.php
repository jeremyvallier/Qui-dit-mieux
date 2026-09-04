<?php
// Rôle : prépare les données nécessaires à l'affichage
//        de la page détail ou de l'historique des enchères.
// Paramètres :
//      GET : id = identifiant de l'annonce
//      GET : historique = 1 si l'utilisateur demande l'historique
require_once "librairie/initialisation.php";
//récupère l'id de l'annonce est le convertit en entier s'il existe sinon l'id vaut 0
$idAnnonce = isset($_GET["id"]) ? (int) $_GET["id"] : 0;

// Vérification de l'identifiant
if ($idAnnonce <= 0) {
    require "templates/pages/accueil.php";
    exit;
}

// Chargement de l'annonce
$annonce = new annonce($idAnnonce);
$annonce->mettreAJourStatuts();
// Vérification que l'annonce existe
if (!$annonce->is()) {
    require "templates/pages/accueil.php";
    exit;
}

// Récupération de la catégorie
// Récupère l'identifiant de la catégorie de l'annonce.
$categorieId = $annonce->get("categorie_id");

// Crée l'URL de l'API avec l'identifiant de la catégorie.
$url = "https://api.mywebecom.ovh/play/qdm/categ.php?id=" . $categorieId;
// Récupère les données de la catégorie depuis l'API.
$json = file_get_contents($url);
// Transforme les données JSON en tableau PHP.
$categorie = json_decode($json, true);
// Récupère le nom de la catégorie.
// Si le nom n'existe pas, affiche "Catégorie inconnue".
$libelleCategorie = $categorie["libelle"] ?? "Catégorie inconnue";

// Récupération des photos
// Crée un objet photo pour pouvoir récupérer les photos de l'annonce.
$photo = new photo();
// Récupère la liste des photos de l'annonce.
$listePhoto = $photo->listePhotos($idAnnonce);
// Récupère la photo principale de l'annonce.
$photoPrincipale = $photo->photoPrincipale($idAnnonce);

// Récupération du prix courant (prix actuel de l'annonce)
$prixActuel = $annonce->prixCourant();

// Récupération du nombre d'enchères déposées sur l'annonce.
$nombreEncheres = $annonce->nombreEncheres();

// Récupération du vendeur
// Crée un objet utilisateur à partir de l'identifiant du vendeur.
$vendeur = new utilisateur(
    $annonce->get("vendeur_id")
);


// --------------------------------------------------
// Vérification de l'accès à l'historique
// --------------------------------------------------
// Par défaut, l'utilisateur n'a pas accès à l'historique.
$accesHistorique = false;
// Par défaut, l'utilisateur ne suit pas l'annonce.
$estSuivi = false;
// Prépare une liste vide pour les enchères.
$listeEncheres = [];

if (isConnected()) {
    // Crée les objets nécessaires pour gérer les enchères et les suivis.
    $enchere = new enchere();
    $suivi = new suivi();

    // Le vendeur peut voir l'historique des enchères.
    if (idConnected() == $annonce->get("vendeur_id")) {
        // Autorise l'accès à l'historique.
        $accesHistorique = true;

    } else {

        // Récupère les enchères faites par l'utilisateur sur cette annonce.
        $listeEncheresUtilisateur =
            $enchere->listeEncheresUtilisateur(
                $idAnnonce,
                idConnected()
            );
        // Si l'utilisateur a déjà enchéri, il peut voir l'historique.
        if (!empty($listeEncheresUtilisateur)) {
            $accesHistorique = true;
        }

        // Vérifie simplement si l'utilisateur suit l'annonce
        if ($suivi->estSuivi(idConnected(), $idAnnonce)) {
            $estSuivi = true;
        }
    }

    // Récupère l'historique complet uniquement si l'utilisateur y a accès.
    if ($accesHistorique) {
        $listeEncheres = $enchere->listeEncheres($idAnnonce);
    }
}


// --------------------------------------------------
// Choix de la page à afficher
// --------------------------------------------------
// Vérifie si l'utilisateur demande à voir l'historique
if (isset($_GET["historique"]) && $_GET["historique"] == 1) {

    // Vérifie si l'utilisateur a le droit de voir l'historique.
    if (!$accesHistorique) {

        // L'utilisateur n'a pas le droit d'y accéder.
        // Affiche le détail de l'annonce à la place.
        $titrePage = "Détail de l'annonce";
        require "templates/pages/detail.php";
        exit;
    }
    // L'utilisateur a le droit d'accéder à l'historique.
    // Affiche la page contenant l'historique des enchères.
    $titrePage = "Historique de l'enchère";
    require "templates/pages/historique_enchere.php";
    exit;
}

// --------------------------------------------------
// Affichage normal de l'annonce
// --------------------------------------------------
$titrePage = "Détail de l'annonce";
require "templates/pages/detail.php";

?>