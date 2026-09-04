<?php
/*
Classe annonce : gestion des annonces du MCD

Cette classe hérite des méthodes génériques de la classe _model.

Seules les méthodes spécifiques à l'objet de ce fichier sont présentes.
*/
require_once "core/_model.php"; // appel à la classe objet générique

class annonce extends _model
{
    // Nom de la table dans phpMyAdmin
    protected $table = "annonce";
    // Nom de ses attributs
    protected $fields = ["titre", "description", "etat", "prix_depart", "date_fin", "vendeur_id", "categorie_id", "date_creation", "statut" ];
    // Lien avec d'autres tables
    protected $links = ["vendeur_id" => "utilisateur"];

    function listeAnnonceEnCours($id)
    {
        // Rôle : récupère les annonces en cours créées
        // par l'utilisateur connecté.
        // Paramètre :
        // $id = identifiant de l'utilisateur connecté
        // Retour :
        // liste d'objets annonce

        $sql = "SELECT " . $this->listFieldsForSelect() . "
                FROM `$this->table`
                WHERE `vendeur_id` = :vendeur_id
                AND `date_fin` > NOW()
                ORDER BY `date_fin` ASC";

        $param = [ ":vendeur_id" => $id ];

        return $this->sqlToTab($sql, $param);
    }

    function listeAnnonceSuiviEncheries($id)
    {
        // Rôle : récupère les annonces en cours suivies sans récupérer plusieurs fois la même annonce
        // ou sur lesquelles l'utilisateur connecté a enchéri.
        // L'utilisateur ne doit pas être le vendeur.
        // Paramètre :
        // $id = identifiant de l'utilisateur connecté
        // Retour :
        // liste d'objets annonce
        $sql = "SELECT DISTINCT
            `annonce`.`id`,
            `annonce`.`titre`,
            `annonce`.`description`,
            `annonce`.`etat`,
            `annonce`.`prix_depart`,
            `annonce`.`date_fin`,
            `annonce`.`vendeur_id`,
            `annonce`.`categorie_id`,
            `annonce`.`date_creation`,
            `annonce`.`statut`
        FROM `annonce`
        /* Relie les annonces aux suivis des utilisateurs*/
        LEFT JOIN `suivi`
            ON `suivi`.`annonce_id` = `annonce`.`id`
        /* Relie les annonces aux enchères */
        LEFT JOIN `enchere`
            ON `enchere`.`annonce_id` = `annonce`.`id`
        WHERE `annonce`.`date_fin` > NOW()
        /* L'utilisateur ne voit pas sa propre annonce dans cette liste*/
        AND `annonce`.`vendeur_id` != :vendeur_id
        /*L'annonce doit être suivie ou avoir fait l'objet d'une enchère */
        AND (
            `suivi`.`utilisateur_id` = :utilisateur_id_suivi
            OR
            `enchere`.`utilisateur_id` = :utilisateur_id_enchere
        )
        /*Affiche les annonces qui se terminent le plus tôt en premier */
        ORDER BY `annonce`.`date_fin` ASC";

        $param = [":vendeur_id" => $id, ":utilisateur_id_suivi" => $id, ":utilisateur_id_enchere" => $id ];

        return $this->sqlToTab($sql, $param);
    }

    function annonceRecente()
    {
        // Rôle : récupère les annonces les plus récemment créées.
        // Paramètre : aucun
        // Retour :
        // liste d'objets annonce

        $sql = "SELECT " . $this->listFieldsForSelect() . "
                FROM `$this->table`
                ORDER BY `date_creation` DESC
                LIMIT 3";

        $param = [];

        return $this->sqlToTab($sql, $param);
    }

    function annonceTrouvee($motCle, $categorie, $etat, $prix, $statut)
    {
        // Rôle : récupère les annonces correspondant
        // aux critères de recherche renseignés.
        // La recherche textuelle porte sur le titre
        // ou la description.
        // Les critères peuvent être combinés.
        // Retour :
        // liste d'objets annonce
        $sql = "SELECT " . $this->listFieldsForSelect() . "
                FROM `$this->table`
                WHERE 1 = 1";
        $param = [];
        // Recherche dans le titre ou la description
        if (!empty($motCle)) {
            $sql .= " AND (
                        `titre` LIKE :motCle
                        OR
                        `description` LIKE :motCle
                    )";
            $param[":motCle"] = "%" . $motCle . "%";
        }
        // Recherche par catégorie
        if (!empty($categorie)) {
            $sql .= " AND `categorie_id` = :categorie";
            $param[":categorie"] = $categorie;
        }
        // Recherche par état
        if (!empty($etat)) {
            $sql .= " AND `etat` = :etat";
            $param[":etat"] = $etat;
        }
        // Recherche par prix
        if ($prix !== "" && $prix !== null) {
            $sql .= " AND `prix_depart` <= :prix";
            $param[":prix"] = $prix;
        }
        // Recherche par statut
        if (!empty($statut)) {
            $sql .= " AND `statut` = :statut";
            $param[":statut"] = $statut;
        }
        // Les annonces les plus récentes en premier
        $sql .= " ORDER BY `date_creation` DESC";
        return $this->sqlToTab($sql, $param);
    }

    function prixCourant()
    {
        // Rôle : récupère le prix courant de l'annonce.
        // S'il n'y a aucune enchère, le prix courant
        // correspond au prix de départ.
        // Retour :
        // prix courant

        $enchere = new enchere();

        $meilleure = $enchere->meilleureEnchere($this->id());

        if ($meilleure === false) {
            return $this->get("prix_depart");
        }

        return $meilleure->get("montant");
    }

    function nombreEncheres()
    {
        // Rôle : récupère le nombre d'enchères
        // placées sur l'annonce.
        // Retour :
        // nombre d'enchères

        // Compte le nombre d'enchères pour cette annonce
        $sql = "SELECT COUNT(*) AS nombre/*AS nombre : donne un nom au résultat */
                FROM `enchere`
                WHERE `annonce_id` = :annonce_id";

        $param = [":annonce_id" => $this->id()];

        $req = $this->execute($sql, $param);

        if ($req === false) {
            return 0;
        }

        $ligne = $req->fetch(PDO::FETCH_ASSOC);

        return $ligne["nombre"];
    }

    function listeEncheresRemportees($id)
    {
        // Rôle : récupère les annonces terminées
        // remportées par l'utilisateur connecté.
        // Paramètre :
        // $id = identifiant de l'utilisateur connecté
        // Retour :
        // liste d'objets annonce

        $sql = "SELECT
                    `annonce`.`id`,
                    `annonce`.`titre`,
                    `annonce`.`description`,
                    `annonce`.`etat`,
                    `annonce`.`prix_depart`,
                    `annonce`.`date_fin`,
                    `annonce`.`vendeur_id`,
                    `annonce`.`categorie_id`,
                    `annonce`.`date_creation`,
                    `annonce`.`statut`
                FROM `annonce`
                /*Relie chaque annonce à ses enchères */
                INNER JOIN `enchere`
                    ON `enchere`.`annonce_id` = `annonce`.`id`
                /* Garde uniquement les annonces terminées */
                WHERE `annonce`.`date_fin` < NOW()
                /*Garde uniquement les enchères de l'utilisateur connecté */
                AND `enchere`.`utilisateur_id` = :utilisateur_id
                /*Vérifie que son enchère est la plus élevée */
                AND `enchere`.`montant` = (
                    /*MAX récupère le montant de l'enchère la plus élevée pour chaque annonce */
                    SELECT MAX(`enchere2`.`montant`)
                    FROM `enchere` AS `enchere2`/*AS enchere2 : donne un nom au résultat */
                    WHERE `enchere2`.`annonce_id` = `annonce`.`id`
                /*Affiche les annonces les plus récemment terminées en premier */
                ) ORDER BY `annonce`.`date_fin` DESC";

        $param = [":utilisateur_id" => $id];

        return $this->sqlToTab($sql, $param);
    }
    function mettreAJourStatuts()
    {
        //Rôle: Termine les annonces dont la date de fin est dépassée et qui sont encore en cours
        //Paramètres : Néant
        //Retour : objet annonce
        $sql = "UPDATE `$this->table` SET `statut` = 'terminee' WHERE `date_fin` <= NOW() AND `statut` = 'enCours'";
        $param = [];
        return $this->execute($sql, $param);
    }
}

?>