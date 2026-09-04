<?php
/*
Classe photo : gestion des photos du MCD

Cette classe hérite des méthodes génériques de la classe _model.

Seules les méthodes spécifiques à l'objet de ce fichier sont présentes.
*/
require_once "core/_model.php"; // appel à la classe objet générique

class photo extends _model
{
    // Nom de la table dans phpMyAdmin
    protected $table = "photo";
    // Nom de ses attributs
    protected $fields = [ "url", "annonce_id", "principale"];
    // Lien avec d'autres tables
    protected $links = [ "annonce_id" => "annonce" ];

    function listePhotos($annonce_id)
    {
        // Rôle : récupère la liste des photos
        // correspondant à une annonce.
        // Paramètre :
        // $annonce_id = identifiant de l'annonce
        // Retour :
        // liste d'objets photo

        $sql = "SELECT " . $this->listFieldsForSelect() . "
                FROM `$this->table`
                WHERE `annonce_id` = :annonce_id
                ORDER BY `principale` DESC, `id` ASC";

        $param = [":annonce_id" => $annonce_id];

        return $this->sqlToTab($sql, $param);
    }


    function photoPrincipale($annonce_id)
    {
        // Rôle : récupère la photo principale
        // d'une annonce.
        // Paramètre :
        // $annonce_id = identifiant de l'annonce
        // Retour :
        // objet photo si une photo principale existe
        // false sinon

        $sql = "SELECT " . $this->listFieldsForSelect() . "
                FROM `$this->table`
                WHERE `annonce_id` = :annonce_id
                AND `principale` = 1
                LIMIT 1";

        $param = [":annonce_id" => $annonce_id];

        $req = $this->execute($sql, $param);

        if ($req === false) {
            return false;
        }

        $ligne = $req->fetch(PDO::FETCH_ASSOC);

        if (empty($ligne)) {
            return false;
        }

        $objet = new static();
        $objet->loadFromTab($ligne);

        return $objet;
    }
}

?>