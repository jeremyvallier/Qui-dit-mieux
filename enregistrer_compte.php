<?php

// Rôle : ce contrôleur enregistre les modifications du compte
//        de l'utilisateur connecté.
//
// Paramètres :
//        POST id => id de l'utilisateur
//        POST pseudo => nouveau pseudo
//        POST email => nouvelle adresse email
//        POST password => nouveau mot de passe, facultatif
//
// Retour :
//        tableau de bord de l'utilisateur
//
// Commentaire : l'utilisateur doit être connecté et ne peut modifier
//               que son propre compte.
require_once "librairie/initialisation.php";
// Vérification de la connexion
if (!isConnected()) {
    require "templates/pages/form_connexion.php";
    exit;
}

// Récupère l'identifiant de l'utilisateur envoyé par le formulaire.
// Convertit la valeur en entier.
// Si aucun identifiant n'est envoyé, utilise 0.
$idUtilisateur = isset($_POST["id"]) ? (int) $_POST["id"] : 0;

if ($idUtilisateur <= 0) {
    require "templates/pages/accueil.php";
    exit;
}

// Vérification que l'utilisateur modifie bien son propre compte
if ($idUtilisateur != idConnected()) {
    require "templates/pages/accueil.php";
    exit;
}

// Récupération de l'utilisateur
$utilisateur = new utilisateur($idUtilisateur);

if (!$utilisateur->is()) {
    require "templates/pages/accueil.php";
    exit;
}

// Récupération des données
// Récupère le pseudo envoyé par le formulaire.
//trim() Supprime les espaces inutiles au début et à la fin.
$pseudo = isset($_POST["pseudo"]) ? trim($_POST["pseudo"]) : "";
// Récupère l'adresse email envoyée par le formulaire.
$email = isset($_POST["email"]) ? trim($_POST["email"]) : "";
// Récupère le mot de passe envoyé par le formulaire.
$password = isset($_POST["password"]) ? $_POST["password"] : "";

// Vérification des champs obligatoires
if ($pseudo == "" || $email == "") {

    $erreur = "Le pseudo et l'adresse email sont obligatoires.";
    $titrePage = "Mon compte";

    require "templates/pages/compte.php";
    exit;
}

// Modification du compte
$utilisateur->modifierCompte($pseudo, $email, $password);
$annonce = new annonce();
$annonce->mettreAJourStatuts();
// Redirige l'utilisateur vers son tableau de bord.(par le biais du contrôleur preparation_tableau_bord.php)
// Ajoute son identifiant dans l'URL.
header("Location: preparation_tableau_bord.php?id=" . $utilisateur->id());
exit;

?>