<?php

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    require_once '../db.php';
    require_once '../models/User.php';
    $Iduser = $_POST['Id'];
    $statusUser = $_POST['status'];

    header('Content-Type: application/json');
    $mysqli = db::connect();

    User::saveStatus($mysqli, $statusUser, $Iduser);

    echo json_encode(['success' => "estatus modificado"]);
    exit;
}
