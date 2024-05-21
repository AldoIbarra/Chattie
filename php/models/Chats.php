<?php

class Chat
{
    private $Id;
    private $Name;
    private $AdminId;
    private $IsGroup;
    private $StatusUser;

    public function getID()
    {
        return $this->Id;
    }

    public function setID($Id)
    {
        $this->Id = $Id;
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

    public function getStatusUser()
    {
        return $this->StatusUser;
    }

    public function setStatusUser($StatusUser)
    {
        $this->StatusUser = $StatusUser;
    }

    public function __construct($Name, $AdminId, $IsGroup, $StatusUser)
    {
        $this->Name = $Name;
        $this->AdminId = $AdminId;
        $this->IsGroup = $IsGroup;
        $this->StatusUser = $StatusUser;
    }

    public static function parseJson($json)
    {
        $Chat = new Chat(
            isset($json['Name']) ? $json['Name'] : '',
            isset($json['AdminId']) ? $json['AdminId'] : '',
            isset($json['IsGroup']) ? $json['IsGroup'] : '',
            isset($json['Status']) ? $json['Status'] : '',
        );
        if (isset($json['Id'])) {
            $Chat->setID((int) $json['Id']);
        }

        return $Chat;
    }

    public static function mostrarChats($mysqli, $IdUser)
    {
        /*$opcion = 'mostrar';
        $sql = 'CALL sp_gestion_Chats(?,?,?,?,?,?)';
        $stmt = $mysqli->prepare($sql);
        $stmt->execute([$opcion, 0, '', '', '', $IdUser]);*/

        $sql = "SELECT c.Id AS 'Id',
        CASE
            WHEN c.IsGroup = 1 THEN c.Name  -- Si es un grupo, muestra el nombre del grupo
            ELSE (SELECT u2.UserName        -- Si no es un grupo, muestra el nombre del otro usuario
                  FROM Users u2 
                  INNER JOIN UserChats uc2 ON u2.Id = uc2.UserId 
                  WHERE uc2.ChatId = c.Id AND uc2.UserId != ?)
        END AS 'Name',
        c.IsGroup,
        CASE                                
        WHEN c.IsGroup = 0 THEN                 -- Si no es grupo, devuelve el status del otro usuario
        (SELECT u2.Status                       
             FROM Users u2
             INNER JOIN UserChats uc2 ON u2.Id = uc2.UserId
             WHERE uc2.ChatId = c.Id AND uc2.UserId != ?)    
        ELSE NULL                               -- De lo contrario devuelve NULL
        END AS 'Status'                         
        FROM Chats c
        INNER JOIN UserChats uc ON uc.ChatId = c.Id
        WHERE uc.UserId = ?";

        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("iii", $IdUser, $IdUser, $IdUser);
        $stmt->execute();
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

    public static function getChatsByUsers($mysqli, $userId, $contactId)
    {
        /*$sql = 'CALL sp_getChatByUsers(?,?)';
        $stmt = $mysqli->prepare($sql);
        $stmt->execute([$userId, $contactId]);*/
        $sql = "SELECT
        c.Id AS 'Id',
        u.UserName AS 'Name',
        0 AS 'IsGroup'
        FROM Chats c
        INNER JOIN Userchats uc ON c.Id = uc.ChatId
        INNER JOIN UserChats uc2 ON c.Id = uc.ChatId
        INNER JOIN Users u ON uc2.UserId = u.Id
        WHERE uc.UserId = ? AND uc2.UserId = ? AND uc.ChatId = uc2.ChatId AND c.IsGroup = 0";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("ii", $userId, $contactId);
        $stmt->execute();
        $result = $stmt->get_result();
        $chat = $result->fetch_assoc();
        return $chat ? Chat::parseJson($chat) : null;
    }

    public static function getChatById($mysqli, $chatId)
    {
        $sql = "SELECT
        c.Id AS 'Id',
        c.Name AS 'Name',
        c.AdminId AS 'AdminId',
        c.IsGroup AS 'IsGroup'
        FROM Chats c
        WHERE c.Id = ?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("i", $chatId);
        $stmt->execute();
        $result = $stmt->get_result();
        $chat = $result->fetch_assoc();
        return $chat ? Chat::parseJson($chat) : null;
    }

    public function createPrivateChat($mysqli, $actualUserId, $contactId){
        $sql = "INSERT INTO Chats
            (Name, AdminId, CreationDate, UpdatedDate, IsGroup, isDataEncrypted)
            VALUES
            (NULL, NULL, now(), now(), 0, 0)";
        $stmt = $mysqli->prepare($sql);
        $stmt->execute();
        $this->Id = (int)$stmt->insert_id;
        $this->relateUserToChat($mysqli, $actualUserId, $this->Id);
        $this->relateUserToChat($mysqli, $contactId, $this->Id);
    }

    public function relateUserToChat($mysqli, $userId, $chatId){
        $sql = "INSERT INTO UserChats
            (ChatId, UserId)
            VALUES
            (?, ?)";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("ii", $chatId, $userId);
        $stmt->execute();
    }

}
