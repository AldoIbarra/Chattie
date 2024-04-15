<?php

class User {
    private $ID;
    private $UserName;
    private $Email;
    private $Password;
    private $DateBirth;
    
    
    public function getID() {
        return $this->ID;
    }
    public function setID($ID) {
        $this->ID = $ID;
    }
    public function getUserName () {
        return $this->UserName;
    }
    public function setUserName($UserName) {
        $this->UserName = $UserName;
    }
    public function getEmail() {
        return $this->Email;
    }
    public function setEmail($Email) {
        $this->Email = $Email;
    }
    public function getPassword() {
        return $this->Password;
    }
    public function setPassword($Password) {
        $this->Password = $Password;
    }
    public function getDateBirth() {
        return $this->DateBirth;
    }
    public function setDateBirth($DateBirth) {
        $this->DateBirth = $DateBirth;
    }
    
    public function __construct($UserName,$Email,$Password, $DateBirth) {
        $this->UserName = $UserName;
        $this->Email = $Email; 
        $this->Password = $Password;
        $this->DateBirth = $DateBirth;
    }
    static public function parseJson($json) {
        $user =  new User(
            isset($json["UserName"]) ? $json["UserName"] : "",
            isset($json["Email"]) ? $json["Email"] : "",
            isset($json["Password"]) ? $json["Password"] : "",
            isset($json["DateBirth"]) ? $json["DateBirth"] : "",
        );
        if(isset($json["ID"]))
            $user->setID((int)$json["ID"]);
        return $user;
    }

    
/*
PROCEDURE `sp_gestion_Usuario`
	opc					
    p_ID				    
    p_UserName				
    p_Email				
    p_Password			
    p_DateBirth		
*/
    public function save($mysqli) {     
        $opcion = 'insertar';
        $ID = 0;
        $sql = "CALL sp_gestion_Usuario(?,?,?,?,?,?)";    
       $stmt= $mysqli->prepare($sql);
         $stmt->execute([$opcion, $ID, $this->UserName, $this->Email, $this->Password, $this->DateBirth]);        
        $this->ID = (int)$stmt->insert_id;
    }

    public static function findUserByUsername($mysqli, $Email, $Password) {
        $opcion = 'signIn';
        $sql = "CALL sp_gestion_Usuario(?,?,?,?,?,?)";    
        $stmt = $mysqli->prepare($sql);
        $stmt->execute([$opcion, 0, "", $Email, $Password, ""]);
        $result = $stmt->get_result(); 
        $user = $result->fetch_assoc();
        return $user ? User::parseJson($user) : NULL;
    }
    
    public static function findUserByID($mysqli, $ID) {
        $opcion = 'findUserById';
        $sql = "CALL sp_gestion_Usuario(?,?,?,?,?,?)";
        $stmt = $mysqli->prepare($sql);
        $stmt->execute([$opcion, $ID, "", "", "", ""]);
        $result = $stmt->get_result(); 
        $user = $result->fetch_assoc();
        return $user ? User::parseJson($user) : NULL;
    }

    public static function BuscarUsuarios($mysqli, $texto) {
        $sql = "CALL sp_buscador(?,?);";
        $stmt= $mysqli->prepare($sql);
        $opcion = "Usuarios";
        $stmt->bind_param("ss", $opcion, $texto);
        $stmt->execute();
        $result = $stmt->get_result(); 
        $users = array();
        while ($user = $result->fetch_assoc()) {
            $users[] = User::parseJson($user);
        }
        return $users;
    }

    public function toJSON() {
        return get_object_vars($this);
    }
}