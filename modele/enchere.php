<?php
/*
Classe enchere : gestion des encheres du MCD

cette classe hérite des méthodes générique de la classe _model

seuls les méthodes spécifique à l'objet de ce fichier sont présentes
*/
require_once "core/_model.php";

class enchere extends _model {
    // Nom de la table dans phpMyAdmin
    protected $table = "enchere";
    // Nom de ses attributs
    protected $fields = ["montant","date_enchere","utilisateur_id", "annonce_id"];
    // Lien avec d'autres tables
    protected $links = ["utilisateur_id" => "utilisateur", "annonce_id" => "annonce"];

    function listeEncheres($id)
    {
        // Rôle : récupère la liste des enchères
        // correspondant à l'annonce sélectionnée.
        // Paramètre :
        // $id = identifiant de l'annonce
        // Retour :
        // liste d'objets enchere

        $sql = "SELECT " . $this->listFieldsForSelect() . "
                FROM `$this->table`
                WHERE `annonce_id` = :annonce_id
                ORDER BY `date_enchere` DESC";

        $param = [":annonce_id" => $id];

        return $this->sqlToTab($sql, $param);
    }

    function meilleureEnchere($annonce_id)
    {
        // Rôle : récupère l'enchère ayant le montant le plus élevé.
        // Paramètre :
        // $annonce_id = identifiant de l'annonce
        // Retour :
        // objet enchere ou false

        $sql = "SELECT " . $this->listFieldsForSelect() . "
                FROM `$this->table`
                WHERE `annonce_id` = :annonce_id
                ORDER BY `montant` DESC, `date_enchere` ASC";

        $param = [":annonce_id" => $annonce_id];

        $req = $this->execute($sql, $param);

        if ($req === false) {
            return false;
        }

        $ligne = $req->fetch(PDO::FETCH_ASSOC);

        if (empty($ligne)) {
            return false;
        }

        $enchere = new static();
        $enchere->loadFromTab($ligne);

        return $enchere;
    }

    function listeEncheresUtilisateur($annonce_id, $utilisateur_id)
    {
        // Rôle : récupère les enchères d'un utilisateur
        // sur une annonce donnée.
        // Paramètres :
        // $annonce_id = identifiant de l'annonce
        // $utilisateur_id = identifiant de l'utilisateur
        // Retour :
        // liste d'objets enchere

        $sql = "SELECT " . $this->listFieldsForSelect() . "
                FROM `$this->table`
                WHERE `annonce_id` = :annonce_id
                AND `utilisateur_id` = :utilisateur_id";

        $param = [":annonce_id" => $annonce_id,":utilisateur_id" => $utilisateur_id];

        return $this->sqlToTab($sql, $param);
    }

}
?>