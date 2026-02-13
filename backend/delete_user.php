<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

include 'db_connect.php'; 

$data = json_decode(file_get_contents("php://input"));

if(isset($data->id)) {
    try {
        $query = "DELETE FROM usuarios WHERE id = :id";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':id', $data->id);
        
        if($stmt->execute()) {
            echo json_encode(["status" => true, "message" => "Usuario eliminado"]);
        } else {
            echo json_encode(["status" => false, "message" => "Error al eliminar"]);
        }
    } catch(PDOException $e) {
        echo json_encode(["status" => false, "message" => $e->getMessage()]);
    }
}
?>                                                                                                                                    <?