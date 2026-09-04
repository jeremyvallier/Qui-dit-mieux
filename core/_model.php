<?php
/*Classe _model : classe générique pour gérer un objet du MCD (notion héritage)
elle contient toutes les méthodes pouvant être appliquées à n'importe quel objet
*/
class _model {
    protected $table = ""; 
    protected $fields = []; 

    protected $id = 0;
    protected $values = []; 

    function __construct($id = null){
    //Rôle : méthode qui se déclenche après une instanciation (new objet), charge une ligne de la table (facultatif)
    //Paramètre : ce que l'on veut en fonction de ce que l'on veut faire à la constuction
    //      $id (facultatif) : id de l'objet' à charger   
    //Retour : cette fonction n'a jamais de retour 
    
        if (! is_null($id)){
            $this->load($id);
        }
    }
    function is(){
        //Rôle : détermine si l'objet existe dans la BDD
        //Paramètre : néant
        //Retour : true s'il existe, sinon false

        return !empty($this->id);
    }
    function get($NomChamp){
        //Rôle : récupère la valeur d'un champ
        //Paramètre : $nomChamp : nom du champ que l'on veut récupérer
        //Retour : la valeur du champ ou null s'il est vide ou inexistant

        if (!in_array($NomChamp, $this->fields)){
            return null;
        }

        if (isset($this->values[$NomChamp])){
            return $this->values[$NomChamp];
        } else {
            return null;
        }
    }
    function id(){
        //Rôle : récupérer la valeur de l'id
        //Paramètre : néant
        //Retour : la valeur de l'id sy elle existe sinon 0

        if (isset($this->id)){
            return $this->id;
        } else {
            return 0;
        }
    }
    function set($nomChamp, $valeur){
        //Rôle : modifier/instancier la valeur d'un champ (sauf celui de l'id)
        //Paramètres: $nomChamp; nom du champ qu'on veut modifier / $valeur; valeur à donner
        //Retour : true si accepté, false si refusé

        if (!in_array($nomChamp, $this->fields)){
            return false;
        }
        $this->values[$nomChamp] = $valeur;
        return true;
    }
    function load($id) {
        // Rôle : charger une ligne de la BDD dans l'objet courant
        // Paramètres :  $id : id à charger
        // Retour : true si réussi, false sinon

        $sql = "SELECT " . $this->listFieldsForSelect() . " FROM `$this->table` WHERE id = :id ";
        $paramValues = [ ":id" => $id ];
        $req = $this->execute($sql, $paramValues);
        if ($req === false) {
            return false;
        }
        $lignes = $req->fetchAll(PDO::FETCH_ASSOC);
        if (empty($lignes)) return false;
        return $this->loadFromTab($lignes[0]);
    }
    function insert() {
        // Rôle : créer la ligne correspondant a l'objet courant dans la BDD (on devra aussi mettre à jour l'id dans l'objet)
        // Paramètres : néant
        // Retour : true si réussi, false sinon

        $sql = "INSERT INTO `$this->table` SET " . $this->listFieldsForSet();
        $param = [];
  
        foreach($this->fields as $nomChamp) { 
            if (isset( $this->values[$nomChamp])){
                $param[":$nomChamp"] = $this->values[$nomChamp];
            } else{
                $param[":$nomChamp"] = null;
            } 
        }
        $req = $this->execute($sql, $param);
        if ($req === false) {
            return false;
        }
        global $bdd;
        $this->id = $bdd->lastInsertId();
        return true;
    }
    function update() {
        // Rôle : mettre à jour la ligne correspondant a l'objet courant dans la BDD 
        // Paramètres : néant
        // Retour : true si réussi, false sinon

        $sql = "UPDATE `$this->table` SET " . $this->listFieldsForSet() . " WHERE id = :id";
        $param = [ ":id" => $this->id ];
        foreach($this->fields as $nomChamp) { 
            if (isset( $this->values[$nomChamp])){
                 $param[":$nomChamp"] = $this->values[$nomChamp];
            }
            else{
                $param[":$nomChamp"] = null;
            } 
        }
        $req = $this->execute($sql, $param);
        if ($req === false) {
            return false;
        }
        return true;
    }
    function delete() {
        // Rôle : supprimer la ligne correspondant a l'objet courant de la BDD (puis remet l'id à zéro dans cet objet, remettre à null tous les champs correspondant à l'id courant)
        // Paramètres : néant
        // Retour : true si réussi, false sinon
        
        $sql = "DELETE FROM `$this->table`  WHERE id = :id";
        $param = [ ":id" => $this->id ];
        $req = $this->execute($sql, $param);
        if ($req === false) {
            return false;
        } 
        $this->id = 0;
        return true;
    }
    function loadByEmail($email) {
        //Rôle : récupère un objet ayant un email donné
        //Paramètre : $email, email qu'on recherche
        //Retour : true si l'email cherché est récupéré, sinon false

        $sql = "SELECT " . $this->listFieldsForSelect() . " FROM `$this->table` WHERE `email` = :email";
        $param = [":email" => $email];
        $req = $this->execute($sql, $param);
        if ($req === false) {
            return false;
        }
        $lignes = $req->fetchAll(PDO::FETCH_ASSOC);
        if (empty($lignes)) return false;
        return $this->loadFromTab($lignes[0]);
    }

    function listeAll() {
        // Rôle : récupérer toutes les objets courant
        // Paramètres :  néant
        // Retour : liste (tableau) d'objets de la classe objet, indexés par leur id

        $sql = "SELECT " . $this->listFieldsForSelect() . " FROM `$this->table`";
        $param = [];
        return $this->sqlToTab($sql, $param);
    }
    function execute($sql, $param = []) {
        // Rôle : Préparer et exécuter une requête dans la BDD, et retourner l'objet requête préparée
        // Paramètres :  $sql : texte de la requête sql (avec des :xxxx) $param : tableau donnant les valeurs de :xxxx
        // retour : objet requête préparée (objet donné par $bdd->prepare()), ou false si échec

        global $bdd;
        $req = $bdd->prepare($sql);
        if ($req === false) {
            return false;
        }

        if ( ! $req->execute($param)) {
            echo $sql;
            print_r($param);
            print_r($this);
             exit;     
        }
        return $req;
    }
    function loadFromTab($tab) {
        // Rôle : valoriser les attributs (id, nom, ....) à partir des éléments d'un tableau
        // Paramètres : $tab : tableau indexé dont les clés sont des noms d'attributs (de colonnes de la table)
        // Retour : true si réussi, false sinon

        foreach($this->fields as $nomAttribut) {
    
            if (isset($tab[$nomAttribut])) {
   
                $this->values[$nomAttribut] = $tab[$nomAttribut];
            }
        }
        if (isset($tab["id"])) {
   
            $this->id = $tab["id"];
        }
        return true;
    }
    function sqlToTab($sql, $param = []) {
        // Rôle : à partir d'une requête sql SELECT, construit une liste d'objet (contenant toutes les taches extraites par la requête SQL)
        // Paramètres (elements de la requêtes SQL)  $sql : texte SQL de la requêtes avec des paramères :xxxx / $param : tableau donnant les valeurs des :xxxx
        // Retour : tableau d'objets de la classe objet, indexé par l'id des objet

        $req = $this->execute($sql, $param);
        $lignes = $req->fetchAll(PDO::FETCH_ASSOC);
        $resultat = [];     
        foreach($lignes as $ligne) {
  
            $objet = new static();      
  
            $objet->loadFromTab($ligne);

            $resultat[$objet->id] = $objet;
        }
        return $resultat;

    }
    function listFieldsForSelect() {
        // Rôle : fournir la liste des champs pour une requête SELECT : `id`, `champs1`, ... 
        // Paramètres : néant
        // Retour : les texte à mettre dans la requête

        $sql = "`id`";
        foreach ($this->fields as $nomChamp) {
            $sql .= ",`$nomChamp`";
        }
        return $sql;
    }
    function listFieldsForSet() {  
        // Rôle : fournir la liste des champs pour une requête : `champs1` = :champ1, `champs1` = :champ1, )
        // Paramètres : néant
        // Retour : les texte à mettre dans la requête
        
        $tab = [];
        foreach($this->fields as $nomChamp) {
            $tab[] = "`$nomChamp` = :$nomChamp";
        }

        return implode(",", $tab);

    }
}
?>