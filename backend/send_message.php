<?php
header('Content-Type: application/json');

require 'db.php';
session_start();

if (!isset($_SESSION['userid'])) {
    echo json_encode(["error" => "User not logged in"]);
    exit;
}

$current_user = $_SESSION['userid'];
$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['userid']) || !isset($data['message'])) {
    echo json_encode(["error" => "Invalid input"]);
    exit;
}

$receiver_id = $data['userid'];
$message = $data['message'];

$stmt = $conn->prepare("INSERT INTO messages (sender_id, receiver_id, content) VALUES (?, ?, ?)");
$stmt->bind_param("iis", $current_user, $receiver_id, $message);

if ($stmt->execute()) {
    echo json_encode(["success" => true]);
} else {
    echo json_encode(["error" => "Failed to send message"]);
}

$stmt->close();
$conn->close();
?>
