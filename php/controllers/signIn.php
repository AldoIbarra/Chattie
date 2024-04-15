<?php
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    require_once "../db.php";
    require_once "../models/User.php";

    //Obtener Json
    $json = json_decode(file_get_contents('php://input'),true);
    
    header('Content-Type: application/json');
    $mysqli = db::connect();

    $user = User::findUserByUsername($mysqli,$json["email"],$json["password"]);

    $json_response = ["success" => true];
    if($user) {
        $json_response["msg" ]= "Bienvenido";
        $json_response ["user"] = $user->toJSON();
        //Inicamos la sesion
        session_start();
        //Guardamos el ID del usuario en la sesion
        $_SESSION["AUTH"] = (string)$user->getId();
        echo json_encode($json_response);
        exit;
    } else {
        $json_response["success"]  = false;
        $json_response["msg"] = "El usuario o la contraseña no son correctos";
        echo json_encode($json_response);
        exit;
    } 
   
}