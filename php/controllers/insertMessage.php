<?php

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    require_once "../db.php";
    require_once "../models/Message.php";

    $UserId = $_POST['fromUser'];
    $ChatId = $_POST['fromChat'];
    $Message = $_POST['message'];
    $Type = $_POST['Type'];
    

    //Obtener Json
    $json = json_decode(file_get_contents('php://input'), true);

    header('Content-Type: application/json');
    $mysqli = db::connect();
    $message = Message::parseJson($json);
    $message->save($mysqli, $ChatId, $UserId , $Message, $Type);

    $json_response = ["success" => true];
        $json_response["msg" ] = "Exito al mandar el mensaje";
        echo json_encode($json_response);
        exit;


}
