<?php
// Rôle : ce contrôleur prépare la page de modification du compte
//        de l'utilisateur connecté.
// Paramètres :
//         GET id => id de l'utilisateur dont il souhaite consulter son compte
// Retour :
//        page compte.php
// Commentaire : l'utilisateur doit être connecté et ne peut modifier
//               que son propre compte.

require_once "librairie/initialisation.php";
// Vérification de la connexion
if (!isConnected()) {
    require "templates/pages/form_connexion.php";
    exit;
}

//récupère l'id de l'annonce est le convertit en entier s'il existe sinon l'id vaut 0
$idUtilisateur = isset($_GET["id"]) ? (int) $_GET["id"] : 0;

if ($idUtilisateur <= 0) {
    require "templates/pages/accueil.php";
    exit;
}

// L'utilisateur ne peut consulter/modifier que son propre compte
if ($idUtilisateur != idConnected()) {
    require "templates/pages/accueil.php";
    exit;
}

// Récupération de l'utilisateur
$utilisateur = new utilisateur($idUtilisateur);
//si l'utilisateur n'existe pas , son id vaut 0 ou n'est pas dans la base de données
if (!$utilisateur->is()) {
    require "templates/pages/accueil.php";
    exit;
}

// Affichage du formulaire
$titrePage = "Mon compte";

require "templates/pages/compte.php";

?>