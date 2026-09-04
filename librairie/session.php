<?php 
//fonctions pour gérer la session

/*
Gestion de $_SESSION :
    connected : true si connecté, false sinon
    id : id de l'utilisateur

On va stocker l'utilisateur connecté dans ue variable gloable
global $userConnected    
*/

function initSession() {
    // Rôle : initialiser les infos de session
    // Paramètres : néant
    // retour : true si on est connecté, false sinon
    session_start();

    // On pourrait ici vérifier que l'utilisateur connecté éventuel existe toujours et est autorisé à se connecté
        //(non géré)
    // On pourrait vérifier des délais de session
        //(non géré)
    return isConnected();
}

function connect($id) {
    // Rôle : déclarer qu'un utilisateur est connecté
    // Paramètres : $id : id de l'utilisateur a connecter
    // Retour : true

    // Mettre true dans l'index connected (de $_SESSION) et l'id dans l'index id
    $_SESSION["connected"] = true;
    $_SESSION["id"] = $id;

    return true;
}

function disconnect() {
    // Rôle : deconnecter l'utilisateur connecté
    // Paramètres : néant
    // Retour : true

    // mettre false dans l'index connected de $_SESSION
    $_SESSION["connected"] = false;

    // effacer l'objet global $userConnected
    global $userConnected;
    $userConnected = null;

    return true;
}

function isConnected() {
    // Rôle : déterminer si on a une connexion active
    // Paramètres : néant
    // Retour : true si on a une connexion active, false sinon

    // Si l'index "connected" de la variable de session n'existe pas ou est false : on n'est pas connecté
    // Sinon on est connecté
    if (empty($_SESSION["connected"])) return false;
    else return true;
}

function userConnected()  {
    // Rôle : retourner l'utilisateur connecté
    // Paramètres : néant
    //Retour : Il est peut être déjà dans la variable globale $userConnected
    global $userConnected;
    if (empty($userConnected)) {
        // On n'a pas déjà créé et chargé $userConnected
        // On connait son id : il est dans $_SESSION["id"]
        
        // Créer un objet l'utilisateur
        $userConnected = new utilisateur();

        // Le charger avec le bon id si on est connecté
        if (isConnected()) $userConnected->load($_SESSION["id"]);
    }
    return $userConnected;
}

function idConnected() {
    // Rôle : retourner l'id de la l'utilisateur connecté
    // Paramètres : néant
    // Retour : id ou 0

    if (isConnected()) return $_SESSION["id"];
    else return 0;
}

?>