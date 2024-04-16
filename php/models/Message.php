<?php

class Message
{
    private $ID;
    private $ChatId;
    private $UserId;
    private $Message;
    private $CreationDate;

    public function getID()
    {
        return $this->ID;
    }

    public function setID($ID)
    {
        $this->ID = $ID;
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

    public function save($mysqli, $ChatId, $UserId ,$Message)
    {
        $opcion = 'insertar';
        $sql = 'CALL sp_gestion_mensajes(?,?,?,?,?,?)';
        $stmt = $mysqli->prepare($sql);
        $stmt->execute([$opcion, 0, $ChatId, $UserId, $Message, ""]);
        $this->ID = (int) $stmt->insert_id;
    }

    public static function mostrarMensajes($mysqli, $IdMessage)
    {
        $opcion = 'Mostrar';
        $sql = 'CALL sp_gestion_mensajes(?,?,?,?,?,?)';
        $stmt = $mysqli->prepare($sql);
        $stmt->execute([$opcion, 0, $IdMessage, 0, '', '']);
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
