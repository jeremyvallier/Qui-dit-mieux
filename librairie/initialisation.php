<?php
//Code pour initialiser les contrôleurs (gérer la connexion, permettre d'intéragir avec la BDD, appeler les objets)

require_once ".gitignore/config.php";

//Gestion des erreurs
ini_set('display_errors',1);
error_reporting(E_ALL);

//Lancement de la session
require_once "librairie/session.php";
initSession();//Fonction pour initié la session utilisateur

// Ouvrir la BDD (variable globale $bdd)
global $bdd; //Déclarer la variable $bdd comme globale
$bdd = new PDO("mysql:host=$host;dbname=$dbname;charset=UTF8", $user, $password);
$bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING) ;  //En mise au point seulement

//Inclure les classes et les fonctions
require_once "core/_model.php";
require_once "modele/annonce.php";
require_once "modele/enchere.php";
require_once "modele/photo.php";
require_once "modele/utilisateur.php";
require_once "modele/suivi.php";
?>