<?php

class Chat
{
    private $ID;
    private $Name;
    private $AdminId;
    private $IsGroup;

    public function getID()
    {
        return $this->ID;
    }

    public function setID($ID)
    {
        $this->ID = $ID;
    }

    public function getName()
    {
        return $this->Name;
    }

    public function setName($Name)
    {
        $this->Name = $Name;
    }

    public function getAdminId()
    {
        return $this->AdminId;
    }

    public function setAdminId($AdminId)
    {
        $this->AdminId = $AdminId;
    }

    public function getIsGroup()
    {
        return $this->IsGroup;
    }

    public function setIsGroup($IsGroup)
    {
        $this->IsGroup = $IsGroup;
    }

    public function __construct($Name, $AdminId, $IsGroup)
    {
        $this->Name = $Name;
        $this->AdminId = $AdminId;
        $this->IsGroup = $IsGroup;
    }

    public static function parseJson($json)
    {
        $Chat = new Chat(
            isset($json['Name']) ? $json['Name'] : '',
            isset($json['AdminId']) ? $json['AdminId'] : '',
            isset($json['IsGroup']) ? $json['IsGroup'] : '',
        );
        if (isset($json['Id'])) {
            $Chat->setID((int) $json['Id']);
        }

        return $Chat;
    }

    public function save($mysqli)
    {
        $opcion = 'insertar';
        $ID = 0;
        $sql = 'CALL sp_gestion_Usuario(?,?,?,?,?,?)';
        $stmt = $mysqli->prepare($sql);
        $stmt->execute([$opcion, $ID, $this->Name, $this->AdminId, $this->IsGroup, $this->DateBirth]);
        $this->ID = (int) $stmt->insert_id;
    }

    public static function mostrarChats($mysqli, $IdUser)
    {
        $opcion = 'mostrar';
        $sql = 'CALL sp_gestion_Chats(?,?,?,?,?,?)';
        $stmt = $mysqli->prepare($sql);
        $stmt->execute([$opcion, 0, '', '', '', $IdUser]);
        $result = $stmt->get_result();
        $Chats = [];
        while ($chat = $result->fetch_assoc()) {
            $Chats[] = Chat::parseJson($chat);
        }

        return $Chats;
    }

    public function toJSON()
    {
        return get_object_vars($this);
    }
}