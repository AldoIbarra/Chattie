<?php

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    require_once '../db.php';
    require_once '../models/User.php';

    // Obtener Json
    $json = json_decode(file_get_contents('php://input'), true);
    $mysqli = db::connect();
    $user = User::parseJson($json);
    try {
        $user->save($mysqli);

        $names = $user->getUserName();
        $json_response = ['success' => true, 'msg' => "Se ha creado el usuario $names"];
    } catch (Exception $e) {
        $error_message = $e->getMessage();

        $json_response = ['success' => false, 'error' => 'Usuario duplicado. Este nombre de usuario ya existe.'];
        if (strpos($error_message, 'Duplicate entry') !== false) {
            $json_response = ['success' => false, 'error' => 'Usuario duplicado. Este nombre de usuario ya existe.'];
        } else {
            // Otro tipo de error
            $json_response = ['success' => false, 'error' => "Error desconocido: $error_message"];
        }
    }

header('Content-Type: application/json');
    echo json_encode($json_response);
}
