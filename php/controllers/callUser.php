<?php
//CONGIFURAR ESTO!!!!!!!!!
// if($_SERVER['REQUEST_METHOD'] == 'POST') {
//     require_once "../db.php";

//     $UserId = $_POST['fromUser'];
//     $ChatId = $_POST['fromChat'];

//     $sql = "INSERT INTO Messages(
//         ChatId, UserId, Message, CreationDate, Status
//         )VALUES( ?,?,?, now(), 1 )";
//     $stmt = $mysqli->prepare($sql);
//     $stmt->bind_param("iis", $ChatId, $UserId, $Message);
//     $stmt->execute();
//     $this->Id = (int) $stmt->insert_id;
    

//     //Obtener Json
//     $json = json_decode(file_get_contents('php://input'), true);

//     header('Content-Type: application/json');
//     $mysqli = db::connect();
//     $message = Message::parseJson($json);
//     $message->save($mysqli, $ChatId, $UserId , $Message);

//     $json_response = ["success" => true];
//         $json_response["msg" ] = "Exito al mandar el mensaje";
//         echo json_encode($json_response);
//         exit;
// }
