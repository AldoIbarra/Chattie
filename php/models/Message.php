<?php

class Message
{
    private $Id;
    private $ChatId;
    private $UserId;
    private $Message;
    private $CreationDate;
    private $isDataEncrypted;

    public function getID()
    {
        return $this->Id;
    }

    public function setID($Id)
    {
        $this->Id = $Id;
    }

    public function getChatId()
    {
        return $this->ChatId;
    }

    public function setChatId($ChatId)
    {
        $this->ChatId = $ChatId;
    }

    public function getUserId()
    {
        return $this->UserId;
    }

    public function setUserId($UserId)
    {
        $this->UserId = $UserId;
    }

    public function getMessage()
    {
        return $this->Message;
    }

    public function setMessage($Message)
    {
        $this->Message = $Message;
    }

    public function getCreationDate()
    {
        return $this->CreationDate;
    }

    public function setCreationDate($CreationDate)
    {
        $this->CreationDate = $CreationDate;
    }

    public function getisDataEncrypted()
    {
        return $this->isDataEncrypted;
    }

    public function setisDataEncrypted($isDataEncrypted)
    {
        $this->isDataEncrypted = $isDataEncrypted;
    }

    public function __construct($ChatId, $UserId, $Message, $CreationDate, $isDataEncrypted)
    {
        $this->ChatId = $ChatId;
        $this->UserId = $UserId;
        $this->Message = $Message;
        $this->CreationDate = $CreationDate;
        $this->isDataEncrypted = $isDataEncrypted;
    }

    public static function parseJson($json)
    {
        $Message = new Message(
            isset($json['ChatId']) ? $json['ChatId'] : '',
            isset($json['UserId']) ? $json['UserId'] : '',
            isset($json['Message']) ? $json['Message'] : '',
            isset($json['CreationDate']) ? $json['CreationDate'] : '',
            isset($json['isDataEncrypted']) ? $json['isDataEncrypted'] : '',
        );
        if (isset($json['Id'])) {
            $Message->setID((int) $json['Id']);
        }

        return $Message;
    }

    public function save($mysqli, $ChatId, $UserId, $Message)
    {
        /*$opcion = 'insertar';
        $sql = 'CALL sp_gestion_mensajes(?,?,?,?,?,?)';
        $stmt = $mysqli->prepare($sql);
        $stmt->execute([$opcion, 0, $ChatId, $UserId, $Message, ""]);*/
        $sql = "INSERT INTO Messages(
            ChatId, UserId, Message, CreationDate, Status
            )VALUES( ?,?,?, now(), 1 )";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("iis", $ChatId, $UserId, $Message);
        $stmt->execute();
        $this->Id = (int) $stmt->insert_id;
    }

    public static function mostrarMensajes($mysqli, $IdMessage)
    {
        /*$opcion = 'Mostrar';
        $sql = 'CALL sp_gestion_mensajes(?,?,?,?,?,?)';
        $stmt = $mysqli->prepare($sql);
        $stmt->execute([$opcion, 0, $IdMessage, 0, '', '']);*/
        $sql = "SELECT u.UserName AS 'UserId', 
        CASE 
        WHEN c.isDataEncrypted = 1 THEN AES_DECRYPT(m.DataEncrypted, 'AES') 
        ELSE m.Message 
        END AS Message, 
        DATE_FORMAT(m.CreationDate, '%Y-%m-%d %H:%i') AS CreationDate, c.isDataEncrypted AS isDataEncrypted
        FROM Messages m
        INNER JOIN Chats c ON m.ChatId = c.Id
        INNER JOIN Users u ON m.UserId = u.Id
        WHERE c.Id = ?
        ORDER BY m.CreationDate";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("i", $IdMessage);
        $stmt->execute();
        $result = $stmt->get_result();
        $messages = [];
        while ($message = $result->fetch_assoc()) {
            $messages[] = $message;
        }

        return $messages;
    }

    public static function EncryptChat($mysqli, $chatId)
    {
        $sql = "SELECT isDataEncrypted FROM Chats WHERE Id = ?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("i", $chatId);
        $stmt->execute();
        $result = $stmt->get_result();
        $isEncrypted = $result->fetch_assoc();

        if ($isEncrypted['isDataEncrypted'] == 0) { //se encriptan los mensajes
            $queryMessage = "UPDATE Messages AS m
            JOIN Chats AS c ON m.ChatId = c.Id
            JOIN Users AS u ON m.UserId = u.Id
            SET m.DataEncrypted = (AES_ENCRYPT(m.Message, 'AES')),
            m.Message = NULL
            WHERE c.Id = ? AND c.isDataEncrypted = 0;";
            $stmt = $mysqli->prepare($queryMessage);
            $stmt->bind_param("i", $chatId);
            $stmt->execute();
            $result = $stmt->get_result();

            $new = 1;
            $queryChat = "UPDATE Chats 
        SET isDataEncrypted = ?
        WHERE Id = ?";
            $stmt = $mysqli->prepare($queryChat);
            $stmt->bind_param("ii", $new, $chatId);
            $stmt->execute();
        } else { //se desencriptan los mensajes
            $query = "UPDATE Messages AS m
            JOIN Chats AS c ON m.ChatId = c.Id
            JOIN Users AS u ON m.UserId = u.Id
            SET m.Message = (AES_DECRYPT(m.DataEncrypted, 'AES')),
            m.DataEncrypted = NULL
            WHERE c.Id = ? AND c.isDataEncrypted = 1;";
            $stmt = $mysqli->prepare($query);
            $stmt->bind_param("i", $chatId);
            $stmt->execute();
            $result = $stmt->get_result();

            $new = 0;
            $queryChat = "UPDATE Chats 
        SET isDataEncrypted = ?
        WHERE Id = ?";
            $stmt = $mysqli->prepare($queryChat);
            $stmt->bind_param("ii", $new, $chatId);
            $stmt->execute();
        }
        return $isEncrypted;
    }

    public function toJSON()
    {
        return get_object_vars($this);
    }
}
