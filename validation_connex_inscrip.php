<?php
// Rôle : ce contrôleur connecte l'utilisateur
//        ou enregistre l'inscription de l'utilisateur.
// Paramètres :
//      pour la connexion
//          POST identifiant
//          POST password
//      pour l'inscription
//          POST pseudo
//          POST email
//          POST passwordI
//      "système" anti-robot simple
// Retour : page accueil.php

require_once "librairie/initialisation.php";

// --------------------------------------------------
// CONNEXION
// --------------------------------------------------
// Vérifie si les données nécessaires à la connexion ont été envoyées.
if (isset($_POST["identifiant"]) && isset($_POST["password"])) {
    // Récupère l'identifiant et supprime les espaces inutiles.
    $identifiant = trim($_POST["identifiant"]);
    // Récupère le mot de passe
    $password = $_POST["password"];
    // Crée un objet utilisateur.
    $utilisateur = new utilisateur();

    // Recherche l'utilisateur avec son pseudo.
    $trouve = $utilisateur->loadByPseudo($identifiant);
    // Si aucun pseudo ne correspond,
    // recherche avec l'email
    if (!$trouve) {
        $trouve = $utilisateur->loadByEmail($identifiant);
    }
    // Vérifie que l'utilisateur existe
    // et que le mot de passe correspond au mot de passe enregistré.
    if ($trouve &&
        password_verify($password, $utilisateur->get("password"))) {
        // Connecte l'utilisateur en enregistrant son identifiant dans la session.
        connect($utilisateur->id());
        // Redirige vers la page d'accueil.
        header("Location: index.php");
        exit;
    }
    // Identifiant ou mot de passe incorrect
    $erreur = "Identifiant ou mot de passe incorrect.";

    require "templates/pages/form_connexion.php";
    exit;
}
// --------------------------------------------------
// INSCRIPTION
// --------------------------------------------------

// Vérifie si les données nécessaires à l'inscription ont été envoyées.
if (isset($_POST["pseudo"]) &&
    isset($_POST["email"]) &&
    isset($_POST["passwordI"])) {

    $pseudo = trim($_POST["pseudo"]);
    $email = trim($_POST["email"]);
    $password = $_POST["passwordI"];
    // Récupère la réponse à la question anti-robot.
    // Si aucune réponse n'est envoyée, utilise 0.
    $antiRobot = isset($_POST["anti_robot"])
    ? (int) $_POST["anti_robot"]
    : 0;
    // Vérifie que la réponse anti-robot est correcte
    if ($antiRobot != 7) {

        $erreur = "La réponse à la question anti-robot est incorrecte.";

        require "templates/pages/form_inscription.php";
        exit;
    }
    // Crée un objet utilisateur pour effectuer les vérifications.
    $utilisateur = new utilisateur();

    // Vérifie si le pseudo est déjà utilisé.
    if ($utilisateur->loadByPseudo($pseudo)) {

        $erreur = "Ce pseudo est déjà utilisé.";

        require "templates/pages/form_inscription.php";
        exit;
    }
    // Vérifie si l'adresse email est déjà utilisée.
    if ($utilisateur->loadByEmail($email)) {

        $erreur = "Cette adresse email est déjà utilisée.";

        require "templates/pages/form_inscription.php";
        exit;
    }
    // Sécurise le mot de passe avant de l'enregistrer en base de données.
    $passwordHash = password_hash($password, PASSWORD_DEFAULT);
    // Crée un nouvel objet utilisateur pour l'inscription.
    $utilisateur = new utilisateur();
    // Enregistre le pseudo.
    $utilisateur->set("pseudo", $pseudo);
    // Enregistre l'adresse email.
    $utilisateur->set("email", $email);
    // Enregistre le mot de passe sécurisé.
    $utilisateur->set("password", $passwordHash);

    // Enregistrement
    $utilisateur->insert();

    // Connexion automatique après inscription
    connect($utilisateur->id());

    header("Location: index.php");
    exit;
}

// --------------------------------------------------
// CAS INATTENDU
// --------------------------------------------------
//aucune donnée de connexion ou d'inscription n'a été reçue
require "templates/pages/accueil.php";

?>

