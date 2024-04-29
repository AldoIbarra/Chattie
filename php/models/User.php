<?php

class User
{
    private $Id;
    private $UserName;
    private $Email;
    private $Password;
    private $DateBirth;


    public function getID()
    {
        return $this->Id;
    }
    public function setID($Id)
    {
        $this->Id = $Id;
    }
    public function getUserName()
    {
        return $this->UserName;
    }
    public function setUserName($UserName)
    {
        $this->UserName = $UserName;
    }
    public function getEmail()
    {
        return $this->Email;
    }
    public function setEmail($Email)
    {
        $this->Email = $Email;
    }
    public function getPassword()
    {
        return $this->Password;
    }
    public function setPassword($Password)
    {
        $this->Password = $Password;
    }
    public function getDateBirth()
    {
        return $this->DateBirth;
    }
    public function setDateBirth($DateBirth)
    {
        $this->DateBirth = $DateBirth;
    }

    public function __construct($UserName, $Email, $Password, $DateBirth)
    {
        $this->UserName = $UserName;
        $this->Email = $Email;
        $this->Password = $Password;
        $this->DateBirth = $DateBirth;
    }
    public static function parseJson($json)
    {
        $user =  new User(
            isset($json["UserName"]) ? $json["UserName"] : "",
            isset($json["Email"]) ? $json["Email"] : "",
            isset($json["Password"]) ? $json["Password"] : "",
            isset($json["DateBirth"]) ? $json["DateBirth"] : "",
        );
        if(isset($json["Id"])) {
            $user->setID((int)$json["Id"]);
        }
        return $user;
    }

    public function save($mysqli)
    {
        /*$opcion = 'insertar';
        $Id = 0;
        $sql = "CALL sp_gestion_Usuario(?,?,?,?,?,?)";
        $stmt = $mysqli->prepare($sql);
        $stmt->execute([$opcion, $Id, $this->UserName, $this->Email, $this->Password, $this->DateBirth]);*/
        $sql = "INSERT INTO Users ( UserName, Email, Password, DateBirth, UserLevel, pointsEarned) VALUES (			
            ?, ?, ?, ?, 1, -- Nivel 0 
            0 -- 0 puntos ganados
            );";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("ssss", $this->UserName, $this->Email, $this->Password, $this->DateBirth);
        $stmt->execute();
        $this->Id = (int)$stmt->insert_id;
    }

    public static function findUserByUsername($mysqli, $Email, $Password)
    {
        /*$opcion = 'signIn';
        $DateBirth = '2024-04-18';
        $sql = "CALL sp_gestion_Usuario(?,?,?,?,?,?)";
        $stmt = $mysqli->prepare($sql);
        $stmt->execute([$opcion, 0, "", $Email, $Password, ""]); */
        $sql = "SELECT Id, UserName, Email, Password, DateBirth FROM Users WHERE Email = ? AND Password = ?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("ss", $Email, $Password);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        return $user ? User::parseJson($user) : null;
    }

    public static function findUserByID($mysqli, $Id)
    {
        /*$opcion = 'findUserById';
        $sql = "CALL sp_gestion_Usuario(?,?,?,?,?,?)";
        $stmt = $mysqli->prepare($sql);
        $stmt->execute([$opcion, $Id, "", "", "", ""]); */
        $sql = "SELECT Id, UserName, Email, Password, DateBirth FROM Users WHERE Id = ? LIMIT 1";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("i", $Id);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        return $user ? User::parseJson($user) : null;
    }

    public static function getUserContacts($mysqli, $Id)
    {
        /*$sql = "CALL sp_gestion_Contacts(?)";
        $stmt = $mysqli->prepare($sql);
        $stmt->execute([$Id]);*/
        $sql = "SELECT u.Id AS 'Id', u.UserName AS 'UserName', u.Email AS 'Email',
        u.DateBirth AS 'DateBirth' FROM Users u WHERE u.Id != ?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("i", $Id);
        $stmt->execute();
        $result = $stmt->get_result();
        $Contacts = [];
        while ($contact = $result->fetch_assoc()) {
            $Contacts[] = User::parseJson($contact);
        }

        return $Contacts;
    }

    public static function BuscarUsuarios($mysqli, $texto)
    {
        $sql = "CALL sp_buscador(?,?);";
        $stmt = $mysqli->prepare($sql);
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

    public function toJSON()
    {
        return get_object_vars($this);
    }
}
