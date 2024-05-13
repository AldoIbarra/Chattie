<?php

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    require_once '../db.php';
    require_once '../models/Message.php';
    $IdChat = $_POST['Id'];

    header('Content-Type: application/json');
    $mysqli = db::connect();

    $isDataEncrypted = Message::EncryptChat($mysqli, $IdChat);

    echo json_encode(['isEncrypted' => $isDataEncrypted]);
    exit;
}
