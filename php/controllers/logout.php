<?php
if($_SERVER['REQUEST_METHOD'] == 'POST') {
require_once "../db.php";
require_once "../models/User.php";

$IdUser = $_POST['Id'];
$mysqli = db::connect();

$status = 0; // Se cambia el estatus del usuario a "No disponible"
User::changeStatusUserToOnline($mysqli, $IdUser, $status); //Cambia el estatus del usuario a "En linea" en cuanto inicia sesión

//Imporante inicializar la sesion antes de destruirla
session_start();
session_destroy();
var_dump("todo bien");
exit;
}