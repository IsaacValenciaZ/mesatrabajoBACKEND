<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

date_default_timezone_set('America/Mexico_City'); 

include_once 'db_connect.php';

$data = json_decode(file_get_contents("php://input"));

if (!empty($data->id) && !empty($data->estado)) {
    
    $id = $data->id;
    $estado = $data->estado;

    $fecha_fin = null;
    
    if ($estado === 'Completo') {
        $fecha_fin = date('Y-m-d H:i:s');
        $query = "UPDATE tickets SET estado = ?, fecha_fin = ? WHERE id = ?";
        $params = [$estado, $fecha_fin, $id];
    } else {
        $query = "UPDATE tickets SET estado = ?, fecha_fin = NULL WHERE id = ?";
        $params = [$estado, $id];
    }
    
    $stmt = $conn->prepare($query);
    
    if ($stmt === false) {
        echo json_encode(["status" => false, "message" => "Error al preparar la consulta"]);
        exit();
    }

    if ($stmt->execute($params)) {
        echo json_encode([
            "status" => true, 
            "message" => "Estado actualizado.", 
            "fecha_fin" => $fecha_fin 
        ]);
    } else {
        $errorInfo = $stmt->errorInfo();
        echo json_encode(["status" => false, "message" => "Error al ejecutar: " . $errorInfo[2]]);
    }

} else {
    echo json_encode(["status" => false, "message" => "Datos incompletos."]);
}

$conn = null;
?>