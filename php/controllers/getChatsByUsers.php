<?php

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    require_once '../db.php';
    require_once '../models/Chats.php';
    $userId = $_POST['userId'];
    $contactId = $_POST['contactId'];

    header('Content-Type: application/json');
    $mysqli = db::connect();

    $chat = Chat::getChatsByUsers($mysqli, $userId, $contactId);

    $json_response = ['success' => true];

    if($chat == null) {
        echo json_encode(null);
    }else{
        echo json_encode($chat->toJSON());
    }
    exit;
}
