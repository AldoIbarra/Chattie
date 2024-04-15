<?php

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    require_once '../db.php';
    require_once '../models/Message.php';
    $IdChat = $_POST['Id'];

    header('Content-Type: application/json');
    $mysqli = db::connect();

    $messages = Message::mostrarMensajes($mysqli, $IdChat);

    $json_response = ['success' => true];

    echo json_encode(['messages' => $messages]);
    exit;
}
