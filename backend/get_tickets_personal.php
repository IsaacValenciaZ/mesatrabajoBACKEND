<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once 'db_connect.php'; 

if (isset($_GET['personal'])) {
    $nombre_personal = $_GET['personal'];

    $query = "SELECT * FROM tickets WHERE personal = ? ORDER BY fecha DESC";
    
    $stmt = $conn->prepare($query);
    $stmt->execute([$nombre_personal]);
    $tickets = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($tickets);
} else {
    echo json_encode([]);
}
$conn = null;
?>