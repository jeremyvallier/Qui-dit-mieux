<?php
/*
Classe utilisateur : gestion des utilisateurs du MCD

Cette classe hérite des méthodes génériques de la classe _model.

Seules les méthodes spécifiques à l'objet de ce fichier sont présentes.
*/
require_once "core/_model.php"; // appel à la classe objet générique

class utilisateur extends _model
{
    // Nom de la table dans phpMyAdmin
    protected $table = "utilisateur";
    // Nom de ses attributs
    protected $fields = [ "pseudo","email", "password"];
    // Liens avec d'autres tables
    protected $links = [];

    function loadByPseudo($pseudo)
    {
        // Rôle : récupère un utilisateur ayant un pseudo donné.
        // Paramètre :
        // $pseudo = pseudo de l'utilisateur recherché
        // Retour :
        // true si l'utilisateur est trouvé
        // false sinon

        $sql = "SELECT " . $this->listFieldsForSelect() . "
                FROM `$this->table`
                WHERE `pseudo` = :pseudo";

        $param = [ ":pseudo" => $pseudo];

        $req = $this->execute($sql, $param);

        if ($req === false) {
            return false;
        }

        $lignes = $req->fetchAll(PDO::FETCH_ASSOC);

        if (empty($lignes)) {
            return false;
        }

        return $this->loadFromTab($lignes[0]);
    }

        function modifierCompte($pseudo, $email, $password = null)
    {
        // Rôle : modifier les informations du compte d'un utilisateur.
        // Paramètres :
        //      $pseudo => nouveau pseudo
        //      $email => nouvelle adresse email
        //      $password => nouveau mot de passe, facultatif
        // Retour :
        //      true si la modification est réussie
        //      false sinon

        if ($password !== null && $password != "") {

            $passwordHash = password_hash($password, PASSWORD_DEFAULT);

            $sql = "UPDATE `$this->table`
                    SET `pseudo` = :pseudo,
                        `email` = :email,
                        `password` = :password
                    WHERE `id` = :id";

            $param = [":pseudo" => $pseudo,":email" => $email,":password" => $passwordHash,":id" => $this->id];

        } else {

            $sql = "UPDATE `$this->table`
                    SET `pseudo` = :pseudo,
                        `email` = :email
                    WHERE `id` = :id";

            $param = [":pseudo" => $pseudo,":email" => $email,":id" => $this->id];
        }

        $req = $this->execute($sql, $param);

        if ($req === false) {
            return false;
        }

        $this->set("pseudo", $pseudo);
        $this->set("email", $email);

        if ($password !== null && $password != "") {
            $this->set("password", $passwordHash);
        }

        return true;
    }
}

?>