<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}

header("Content-Type: application/json; charset=UTF-8");

require_once "db_connect.php"; 

$data = json_decode(file_get_contents("php://input"));

if(!isset($data->id) || !isset($data->nombre) || !isset($data->email)) {
    echo json_encode(["status" => false, "message" => "Faltan datos"]);
    exit;
}

$id = (int)$data->id;
$nombre = trim($data->nombre);
$email = trim($data->email);
$pass = isset($data->password) ? trim($data->password) : "";

$check = $conn->prepare("SELECT id FROM usuarios WHERE email = :email AND id != :id");
$check->bindParam(':email', $email);
$check->bindParam(':id', $id);
$check->execute();

if($check->rowCount() > 0) {
    echo json_encode(["status" => false, "message" => "Ese correo ya está en uso por otro usuario"]);
    exit;
}

if (!empty($pass)) {
    $hash = password_hash($pass, PASSWORD_DEFAULT);
    $sql = "UPDATE usuarios SET nombre = :n, email = :e, password = :p WHERE id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':p', $hash);
} else {
    $sql = "UPDATE usuarios SET nombre = :n, email = :e WHERE id = :id";
    $stmt = $conn->prepare($sql);
}

$stmt->bindParam(':n', $nombre);
$stmt->bindParam(':e', $email);
$stmt->bindParam(':id', $id);

if($stmt->execute()) {
    echo json_encode(["status" => true, "message" => "Datos actualizados"]);
} else {
    echo json_encode(["status" => false, "message" => "Error al guardar en BD"]);
}
?>