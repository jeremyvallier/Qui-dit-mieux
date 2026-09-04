<?php
//Rôle : ce contrôleur enregistre l’édition/suppression d’une annonce
//Paramètres : pour la création / modification d'un objet annonce
//              POST id
//              POST titre
//              POST description
//              POST categorie_id
//              POST etat
//              POST prix
//              POST date_fin
//              POST date_creation
//              POST statut
//              FILE principale
//              FILE photos[]
//
//              ou pour supprimer l'objet annonce
//              POST supprimer
//Retour : page tableau_de_bord.php
//Commentaire : l'utilisateur doit être connecté sinon ce contrôleur affiche
//              la page de connexion
//              + doit être le créateur de l'annonce à modifier/supprimer

require_once "librairie/initialisation.php";

if (!isConnected()) {

    require "templates/pages/form_connexion.php";
    exit;

}

$idAnnonce = isset($_POST["id"]) ? (int) $_POST["id"] : 0;

/*
 SUPPRESSION
 La suppression concerne uniquement
 une annonce existante.
*/

if (isset($_POST["supprimer"])) {

    if ($idAnnonce <= 0) {

        header("Location: preparation_tableau_bord.php");
        exit;
    }

    $annonce = new annonce($idAnnonce);
    $annonce->mettreAJourStatuts();

    $annonce = new annonce($idAnnonce);

    if (!$annonce->is()) {
        require "templates/pages/accueil.php";
        exit;

    }

    if ($annonce->get("vendeur_id") != idConnected()) {
        require "templates/pages/accueil.php";
        exit;
    }
    if ($annonce->get("statut") == "terminee") {
        header("Location: preparation_tableau_bord.php");
        exit;
    }

    $photo = new photo();
    $listePhoto = $photo->listePhotos($idAnnonce);

    foreach ($listePhoto as $photoAnnonce) {
        $photoAnnonce->delete();

    }

    $annonce->delete();
    header("Location: preparation_tableau_bord.php");
    exit;
}

//ENREGISTREMENT
if (isset($_POST["publier"])) {
    $titre = isset($_POST["titre"])
        ? trim($_POST["titre"])
        : "";
    $description = isset($_POST["description"])
        ? trim($_POST["description"])
        : "";
    $categorie = isset($_POST["categorie_id"])
        ? (int) $_POST["categorie_id"]//transforme en entier
        : 0;
    $etat = isset($_POST["etat"])
        ? $_POST["etat"]
        : "";
    $prix = isset($_POST["prix"])
        ? (float) $_POST["prix"] // Convertit la valeur en nombre décimal.
        : 0; // Si aucun prix n'est envoyé, utilise 0.
    $dateFin = isset($_POST["date_fin"])
        ? $_POST["date_fin"]
        : "";
    $dateCreation = isset($_POST["date_creation"])
        ? $_POST["date_creation"]
        : date("Y-m-d H:i:s");
    //Vérification des champs
    if (
        $titre == "" ||
        $description == "" ||
        $categorie <= 0 ||
        $etat == "" ||
        $prix <= 0 ||
        $dateFin == ""
    ) {
        $erreur = "Tous les champs sont obligatoires.";
        require "templates/pages/edition.php";
        exit;
    }
    //On indique si l'annonce est nouvelle.
    $nouvelleAnnonce = false;
    //Création d'une nouvelle annonce
    if ($idAnnonce <= 0) {
        $nouvelleAnnonce = true;
        $annonce = new annonce();
        $annonce->set("titre", $titre);
        $annonce->set("description", $description);
        $annonce->set("categorie_id", $categorie);
        $annonce->set("etat", $etat);
        $annonce->set("prix_depart", $prix);
        $annonce->set("date_fin", $dateFin);
        $annonce->set("vendeur_id", idConnected());
        $annonce->set("date_creation", $dateCreation);
        $annonce->set("statut", "enCours");
        $annonce->insert();
        $idAnnonce = $annonce->id();
    }
    //Modification d'une annonce existante
    else {
        $annonce = new annonce($idAnnonce);
        if (!$annonce->is()) {
            require "templates/pages/accueil.php";
            exit;
        }

        if ($annonce->get("vendeur_id") != idConnected()) {
            require "templates/pages/accueil.php";
            exit;
        }
        $annonce->set("titre", $titre);
        $annonce->set("description", $description);
        $annonce->set("categorie_id", $categorie);
        $annonce->set("etat", $etat);
        $annonce->set("prix_depart", $prix);
        $annonce->set("date_fin", $dateFin);
        $annonce->update();

    }
 
 // Photo principale
if (
    isset($_FILES["principale"]) &&
    $_FILES["principale"]["error"] == 0
) {

    // Suppression de l'ancienne photo principale
    // uniquement lors d'une modification.
    if (!$nouvelleAnnonce) {
        $photo = new photo();
        $listePhoto = $photo->listePhotos($idAnnonce);

        foreach ($listePhoto as $photoAnnonce) {
            if ($photoAnnonce->get("principale") == 1) {
                $photoAnnonce->delete();
            }
        }
    }

    // Ajout de la nouvelle photo principale
    // Crée un nom unique pour éviter qu'une autre photo soit écrasée.
    $nomPhoto = uniqid() . "_" . 
        basename($_FILES["principale"]["name"]);

    // Chemin physique sur le serveur
    $chemin = __DIR__ . "/images/" . $nomPhoto;

    // Chemin enregistré dans la base
    $url = "images/" . $nomPhoto;
    // Déplace la photo envoyée vers le dossier images.
    // La fonction retourne true si le déplacement a réussi
    if (move_uploaded_file(
        $_FILES["principale"]["tmp_name"],
        $chemin
    )) {
        
        $photo = new photo();
        $photo->set("url", $url);
        $photo->set("annonce_id", $idAnnonce);
        $photo->set("principale", 1);
        $photo->insert();
    }
}

// Photos secondaires
if (isset($_FILES["photos"])) {

    $photo = new photo();
    $listePhoto = $photo->listePhotos($idAnnonce);

    $nombrePhotosSecondaires = 0;

    foreach ($listePhoto as $photoAnnonce) {
        if ($photoAnnonce->get("principale") == 0) {
            $nombrePhotosSecondaires++;
        }
    }

    // Jusqu'à 3 photos secondaires maximum
    foreach ($_FILES["photos"]["name"] as $indice => $nomOriginal) {

        if ($nombrePhotosSecondaires >= 3) {
            break;
        }
        // Vérifie si le fichier a bien été envoyé sans erreur.
        if ($_FILES["photos"]["error"][$indice] == 0) {

            $nomPhoto = uniqid() . "_" .
                basename($nomOriginal);

            // Chemin physique sur le serveur
            $chemin = __DIR__ . "/images/" . $nomPhoto;

            // Chemin enregistré dans la base
            $url = "images/" . $nomPhoto;

            if (move_uploaded_file(
                $_FILES["photos"]["tmp_name"][$indice],
                $chemin
            )) {

                $photo = new photo();
                $photo->set("url", $url);
                $photo->set("annonce_id", $idAnnonce);
                $photo->set("principale", 0);
                $photo->insert();

                $nombrePhotosSecondaires++;
            }
        }
    }
}
    //Retour au tableau de bord
    header("Location: preparation_tableau_bord.php");
    exit;
}
// Si aucune action n'est demandée
require "preparation_tableau_bord.php";
?>