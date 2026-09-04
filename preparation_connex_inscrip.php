<?php
// Rôle : ce contrôleur génère la page form_connexion.php
//        ou la page form_inscription.php.
// Paramètres :
//      GET : action = connexion ou inscription
// Retour :
//      Soit la page form_connexion.php,
//      soit la page form_inscription.php.
require_once "librairie/initialisation.php";

// Récupère l'action envoyée dans l'URL.
// Si aucune action n'est indiquée, utilise "connexion".
$action = isset($_GET["action"]) ? $_GET["action"] : "connexion";

// Vérifie si l'utilisateur souhaite accéder à l'inscription.
if ($action == "inscription") {
    // Affiche le formulaire d'inscription
    require "templates/pages/form_inscription.php";
    exit;

}

// Si l'action n'est pas "inscription", affiche le formulaire de connexion.
require "templates/pages/form_connexion.php";
?>