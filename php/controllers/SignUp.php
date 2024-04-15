<?php

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    require_once '../db.php';
    require_once '../models/User.php';

    // Obtener Json
    $json = json_decode(file_get_contents('php://input'), true);

    // Sanitizar JSON
    // $filters = [
    //     'names' => FILTER_SANITIZE_STRING,
    //     'lastnames' => FILTER_SANITIZE_STRING,
    //     'username' => FILTER_SANITIZE_STRING,
    //     'email' => FILTER_VALIDATE_EMAIL,
    //     'password' => FILTER_SANITIZE_STRING
    // ];
    // $options = [
    //     'names' =>  [ 'flags' => FILTER_NULL_ON_FAILURE ],
    //     'lastnames' =>  [ 'flags' => FILTER_NULL_ON_FAILURE ],
    //     'username' =>  [ 'flags' => FILTER_NULL_ON_FAILURE ],
    //     'email' =>  [ 'flags' => FILTER_NULL_ON_FAILURE ],
    //     'password' =>  [ 'flags' => FILTER_NULL_ON_FAILURE ],
    // ];
    // $json_safe = [];
    // foreach($json as $key=>$value) {
    //     $json_safe[$key] = filter_var($value, $filters[$key], $options[$key]);
    // }
    $mysqli = db::connect();
    $user = User::parseJson($json);
    try {
        $user->save($mysqli);

        $names = $user->getUser_name();
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
