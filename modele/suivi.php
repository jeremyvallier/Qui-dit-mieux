<?php
/*
Classe suivi : gestion des suivis du MCD
Cette classe hérite des méthodes génériques de la classe _model.
Seules les méthodes spécifiques à l'objet de ce fichier sont présentes.
*/
require_once "core/_model.php"; // appel à la classe objet générique

class suivi extends _model
{
    // Nom de la table dans phpMyAdmin
    protected $table = "suivi";
    // Nom de ses attributs
    protected $fields = ["utilisateur_id", "annonce_id", "date_suivi"];
    // Liens avec d'autres tables
    protected $links = ["utilisateur_id" => "utilisateur", "annonce_id" => "annonce"];
    // Fait le lien entre un utilisateur
    // et une annonce que celui-ci suit

    function estSuivi($utilisateur_id, $annonce_id)
    {
        // Rôle : vérifie si l'utilisateur suit l'annonce.
        // Paramètres :
        // $utilisateur_id = identifiant de l'utilisateur
        // $annonce_id = identifiant de l'annonce
        // Retour :
        // true si le suivi existe
        // false sinon
        $sql = "SELECT *
                FROM `suivi`
                WHERE `utilisateur_id` = :utilisateur_id
                AND `annonce_id` = :annonce_id";

        $param = [":utilisateur_id" => $utilisateur_id,":annonce_id" => $annonce_id];

        $req = $this->execute($sql, $param);
        if ($req === false) {
            return false;
        }
        $ligne = $req->fetch(PDO::FETCH_ASSOC);

        if (empty($ligne)) {
            return false;
        }
        return true;
    }
    function ajouterSuivi($utilisateur_id, $annonce_id)
    {
        // Rôle : ajoute un suivi entre un utilisateur et une annonce.
        // Paramètres :
        // $utilisateur_id = identifiant de l'utilisateur
        // $annonce_id = identifiant de l'annonce
        // Retour :
        // true si le suivi a été ajouté
        // false sinon

        // Vérifie que le suivi n'existe pas déjà
        if ($this->estSuivi($utilisateur_id, $annonce_id)) {
            return false;
        }
        $sql = "INSERT INTO `suivi`
                (`utilisateur_id`, `annonce_id`, `date_suivi`)
                VALUES
                (:utilisateur_id, :annonce_id, NOW())";

        $param = [ ":utilisateur_id" => $utilisateur_id, ":annonce_id" => $annonce_id];
        $req = $this->execute($sql, $param);
        if ($req === false) {
            return false;
        }
        return true;
    }
}
?>