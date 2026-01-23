<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once 'db_connect.php'; 

if (isset($_GET['personal'])) {
    $personal = $_GET['personal'];

    $query = "SELECT * FROM tickets WHERE personal = ? ORDER BY fecha DESC";
    
    $stmt = $conn->prepare($query);

    if ($stmt === false) {
        http_response_code(500);
        echo json_encode(["status" => false, "message" => "Error SQL al preparar"]);
        exit();
    }

    $stmt->execute([$personal]);

    $tickets = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($tickets);
} else {
    echo json_encode([]);
}

$conn = null;
?>