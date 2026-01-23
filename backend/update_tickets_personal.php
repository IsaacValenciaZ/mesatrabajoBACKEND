<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

include_once 'db_connect.php';

$data = json_decode(file_get_contents("php://input"));

if (!empty($data->id) && !empty($data->estado)) {
    
    $id = $data->id;
    $estado = $data->estado;

    $query = "UPDATE tickets SET estado = ? WHERE id = ?";
    
    $stmt = $conn->prepare($query);
    
    if ($stmt === false) {
        echo json_encode(["status" => false, "message" => "Error al preparar la consulta"]);
        exit();
    }

    if ($stmt->execute([$estado, $id])) {
        echo json_encode(["status" => true, "message" => "Estado actualizado correctamente."]);
    } else {
        
        $errorInfo = $stmt->errorInfo();
        echo json_encode(["status" => false, "message" => "Error al ejecutar: " . $errorInfo[2]]);
    }

} else {
    echo json_encode(["status" => false, "message" => "Datos incompletos."]);
}

$conn = null;
?>