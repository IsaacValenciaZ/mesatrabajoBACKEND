<?php
date_default_timezone_set('America/Mexico_City'); 
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");
include_once("db_connect.php");

$data = json_decode(file_get_contents("php://input"));

if(isset($data->nombre_usuario) && isset($data->personal) && isset($data->descripcion)) {
    try {
        $fecha_limite = date('Y-m-d H:i:s', strtotime('+24 hours'));
        
        $adminId = isset($data->admin_id) ? $data->admin_id : null;

        $sql = "INSERT INTO tickets (nombre_usuario, departamento, descripcion, prioridad, personal, notas, fecha_limite, fecha_fin, admin_id) 
                VALUES (:user, :depto, :desc, :prio, :pers, :notas, :limite, NULL, :adminId)";
        
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            ':user' => $data->nombre_usuario,
            ':depto' => $data->departamento,
            ':desc' => $data->descripcion,     
            ':prio' => $data->prioridad,
            ':pers' => $data->personal,
            ':notas' => $data->notas,
            ':limite' => $fecha_limite,
            ':adminId' => $adminId 
        ]);

        echo json_encode(["status" => true, "message" => "Ticket creado correctamente"]);
    } catch (PDOException $e) {
        echo json_encode(["status" => false, "error" => $e->getMessage()]);
    }
} else {
    echo json_encode(["status" => false, "message" => "Faltan datos obligatorios"]);
}
?>