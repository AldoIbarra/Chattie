<?php

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    require_once "../db.php";
    require_once "../models/Chats.php";

    $actualUserId = $_POST['actualUserId'];
    $contactId = $_POST['contactId'];

    //Obtener Json
    $json = null;

    header('Content-Type: application/json');
    $mysqli = db::connect();
    $chat = Chat::parseJson($json);
    $chat->createPrivateChat($mysqli, $actualUserId, $contactId);

    $json_response = ["success" => true];
    $json_response["msg" ] = "Exito al crear el chat";
    echo json_encode($json_response);
    exit;
}
