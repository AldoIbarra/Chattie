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
    $message->save($mysqli, $ChatId, $UserId, $Message, $Type);

    if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['file']['tmp_name'];
        $fileName = basename($_FILES['file']['name']);
        $fileType = $_FILES['file']['type'];
        $fileSize = $_FILES['file']['size'];
        $filePath = '../../files/' . $fileName;

        if (!is_dir('../../files')) { //se crea la carpeta de destino si no existe
            mkdir('../../files', 0777, true);
        }

        if (move_uploaded_file($fileTmpPath, $filePath)) {
            $messageFile = Message::parseJson($json);
            $messageFile->saveFile($mysqli, $message->getID(), $fileName, $filePath, $fileType, $fileSize);
            echo "Archivo subido exitosamente.";
        } else {
            echo "Error al mover el archivo subido.";
        }
    } else {
        echo "No se ha subido ningún archivo o ha ocurrido un error.";
    }



    // $json_response = ["success" => true];
    //     $json_response["msg" ] = "Exito al mandar el mensaje";
    //     echo json_encode($json_response);
    //     exit;


}
