<?php

class Message
{
    private $Id;
    private $ChatId;
    private $UserId;
    private $Message;
    private $CreationDate;

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

    public function __construct($ChatId, $UserId, $Message, $CreationDate)
    {
        $this->ChatId = $ChatId;
        $this->UserId = $UserId;
        $this->Message = $Message;
        $this->CreationDate = $CreationDate;
    }

    public static function parseJson($json)
    {
        $Message = new Message(
            isset($json['ChatId']) ? $json['ChatId'] : '',
            isset($json['UserId']) ? $json['UserId'] : '',
            isset($json['Message']) ? $json['Message'] : '',
            isset($json['CreationDate']) ? $json['CreationDate'] : '',
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
        $sql = "SELECT u.UserName AS 'UserId', m.Message, DATE_FORMAT(m.CreationDate, '%Y-%m-%d %H:%i') AS CreationDate 
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

    public function toJSON()
    {
        return get_object_vars($this);
    }
}
