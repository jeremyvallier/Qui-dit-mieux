<?php
// Rôle : ce contrôleur fournit les informations nécessaires
//        au rafraîchissement AJAX des annonces et des enchères.
// Paramètres :
//        type => type de rafraîchissement demandé
//        id   => id de l'annonce
// Retour :
//        données JSON pour JavaScript
// Commentaire : ce contrôleur est appelé régulièrement par fonctions.js

require_once "librairie/initialisation.php";
// Indique que la réponse envoyée sera au format JSON.
header("Content-Type: application/json; charset=utf-8");

// Récupère le type de rafraîchissement demandé.
$type = isset($_GET["type"]) ? $_GET["type"] : "";
// Récupère l'identifiant de l'annonce et le convertit en entier.
$idAnnonce = isset($_GET["id"]) ? (int) $_GET["id"] : 0;

// Vérifie que l'identifiant de l'annonce est valide.
if ($idAnnonce <= 0) {
    echo json_encode([
        "erreur" => true
    ]);
    exit;
}
// Charge l'annonce correspondant à l'identifiant reçu.
$annonce = new annonce($idAnnonce);
// Vérifie que l'annonce existe.
if (!$annonce->is()) {
    echo json_encode([
        "erreur" => true
    ]);
    exit;
}
// Récupère les informations qui peuvent être actualisées.
$prixActuel = $annonce->prixCourant();
$nombreEncheres = $annonce->nombreEncheres();
// Prépare les données communes qui seront envoyées en JSON.
$resultat = [
    "erreur" => false,
    "id" => $idAnnonce,
    "prix" => $prixActuel,
    "date_fin" => $annonce->get("date_fin"),
    "nombre_encheres" => $nombreEncheres
];
/*
Rafraîchissement d'une annonce appartenant
à l'utilisateur connecté.
 */
if ($type == "annonce") {
    // Seul un utilisateur connecté peut accéder à ces informations
    if (!isConnected()) {
        echo json_encode([
            "erreur" => true
        ]);
        exit;
    }
    // Vérifie que l'utilisateur connecté est bien le vendeur.
    if ($annonce->get("vendeur_id") != idConnected()) {
        echo json_encode([
            "erreur" => true
        ]);
        exit;
    }
    // Vérifie si la date de fin de l'annonce est dépassée.
    if ($annonce->get("date_fin") < date("Y-m-d H:i:s")) {
        // L'enchère est terminée.
        $resultat["terminee"] = true;
        // Une annonce avec au moins une enchère est considérée comme vendue.
        if ($nombreEncheres > 0) {
            $resultat["vendu"] = true;
        } else {
            $resultat["vendu"] = false;
        }

    } else {
        // L'enchère n'est pas encore terminée.
        $resultat["terminee"] = false;
        $resultat["vendu"] = false;
    }
    // Envoie les informations au JavaScript au format JSON.
    echo json_encode($resultat);
    exit;
}

/*
Rafraîchissement d'une annonce suivie
ou sur laquelle l'utilisateur a enchéri.
 */
if ($type == "suivi") {
    // Seul un utilisateur connecté peut accéder à ces informations.
    if (!isConnected()) {
        echo json_encode([
            "erreur" => true
        ]);
        exit;
    }
    // Le vendeur ne peut pas utiliser ce type de rafraîchissement
    // pour sa propre annonce.
    if ($annonce->get("vendeur_id") == idConnected()) {
        echo json_encode([
            "erreur" => true
        ]);
        exit;
    }
    // Crée un objet enchere pour rechercher la meilleure enchère.
    $enchere = new enchere();
    // Récupère la meilleure enchère de l'annonce.
    $meilleureEnchere = $enchere->meilleureEnchere($idAnnonce);
    // Par défaut, l'utilisateur connecté n'est pas le meilleur enchérisseur.
    $resultat["meilleur_encherisseur"] = false;

    if ($meilleureEnchere !== false) {
        // Vérifie si la meilleure enchère appartient à l'utilisateur connecté.
        if ($meilleureEnchere->get("utilisateur_id") == idConnected()) {
            $resultat["meilleur_encherisseur"] = true;
        }
    }
    // Vérifie si l'enchère est terminée.
    if ($annonce->get("date_fin") < date("Y-m-d H:i:s")) {
        $resultat["terminee"] = true;
    } else {
        $resultat["terminee"] = false;
    }
    // Envoie les informations au JavaScript au format JSON.
    echo json_encode($resultat);
    exit;
}

/*
Rafraîchissement de l'historique des enchères.
On renvoie directement le HTML du fragment encherisseur.php.
 */
if ($type == "encherisseur") {
    // Crée un objet enchere pour récupérer les enchères.
    $enchere = new enchere();
    // Commence à enregistrer temporairement le HTML généré.
    $listeEncheres = $enchere->listeEncheres($idAnnonce);
    // Commence à enregistrer temporairement le HTML généré.
    ob_start();
    // Parcourt toutes les enchères de l'annonce.
    foreach ($listeEncheres as $enchere) {
        // Génère le HTML d'une enchère.
        require "templates/fragments/encherisseur.php";
    }
    // Récupère tout le HTML généré précédemment.
    $html = ob_get_clean();
    // Envoie le HTML au JavaScript au format JSON.
    echo json_encode([
        "erreur" => false,
        "html" => $html
    ]);

    exit;
}

/*
 * Type inconnu.
 */
// Retourne une erreur si le type demandé n'est pas reconnu.
echo json_encode([
    "erreur" => true
]);

?>

