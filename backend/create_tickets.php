<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");
include_once("db_connect.php");

$data = json_decode(file_get_contents("php://input"));

if(isset($data->nombre_usuario) && isset($data->personal) && isset($data->descripcion)) {
    try {
        
        
        $sql = "INSERT INTO tickets (nombre_usuario, departamento, descripcion, prioridad, personal, notas) 
                VALUES (:user, :depto, :desc, :prio, :pers, :notas)";
        
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            ':user' => $data->nombre_usuario,
            ':depto' => $data->departamento,
            ':desc' => $data->descripcion,     
            ':prio' => $data->prioridad,
            ':pers' => $data->personal,
            ':notas' => $data->notas
        ]);

        echo json_encode(["status" => true, "message" => "Ticket creado"]);
    } catch (PDOException $e) {
        echo json_encode(["status" => false, "error" => $e->getMessage()]);
    }
} else {
    echo json_encode(["status" => false, "message" => "Faltan datos obligatorios"]);
}
?>