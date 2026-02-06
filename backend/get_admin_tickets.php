<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
include_once 'db_connect.php'; 

if (isset($_GET['id'])) {
    $admin_id = $_GET['id'];


    $query = "SELECT t.*, u.nombre as nombre_creador 
              FROM tickets t
              LEFT JOIN usuarios u ON t.admin_id = u.id
              WHERE t.admin_id = ? 
              ORDER BY t.fecha DESC";
    
    $stmt = $conn->prepare($query);
    if ($stmt === false) {
        http_response_code(500);
        echo json_encode(["error" => "Error SQL"]);
        exit;
    }

    $stmt->execute([$admin_id]);
    $tickets = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($tickets);
} else {
    echo json_encode([]);
}
?>