<?php
// Rôle : ce contrôleur prépare les données nécessaires
//        à l'affichage de la page recherche.php.
// Paramètres : néant
// Retour : page recherche.php

require_once "librairie/initialisation.php";

// Récupération des catégories
$url = "https://api.mywebecom.ovh/play/qdm/categ.php";
// Récupère les données des catégories depuis l'API.
$json = file_get_contents($url);
// Vérifie que les données ont bien été récupérées.
if ($json === false) {
    $categories = [];
} else {
    // Transforme les données JSON en tableau PHP
    $categories = json_decode($json, true);
    // Vérifie que les catégories sont bien dans un tableau.
    if (!is_array($categories)) {
        $categories = [];
    }
}

// Affichage de la page recherche
require "templates/pages/recherche.php";

?>