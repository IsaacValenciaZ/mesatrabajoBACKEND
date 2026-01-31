<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once 'db_connect.php'; 

if (isset($_GET['id'])) {
    $admin_id = $_GET['id'];

    $query = "SELECT * FROM tickets WHERE admin_id = ? ORDER BY fecha DESC";
    
    $stmt = $conn->prepare($query);
    if ($stmt === false) {
        http_response_code(500);
        echo json_encode(["status" => false, "message" => "Error SQL"]);
        exit();
    }
    $stmt->execute([$admin_id]);
    echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
} else {
    echo json_encode([]);
}
$conn = null;
?>