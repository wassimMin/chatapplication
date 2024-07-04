<?php

require 'db.php';
session_start();

if (!isset($_SESSION['userid'])) {
    echo json_encode(["error" => "User not logged in"]);
    exit;
}

$current_user = $_SESSION['userid'];
$chat_user_id = $_GET['userid'];

if (!is_numeric($chat_user_id)) {
    echo json_encode(["error" => "Invalid user ID"]);
    exit;
}

$stmt = $conn->prepare("SELECT * FROM messages WHERE (sender_id = ? AND receiver_id = ?) OR (sender_id = ? AND receiver_id = ?) ORDER BY created_at ASC");
$stmt->bind_param("iiii", $current_user, $chat_user_id, $chat_user_id, $current_user);
$stmt->execute();
$result = $stmt->get_result();

$messages = [];
while ($row = $result->fetch_assoc()) {
    $messages[] = $row;
}

echo json_encode($messages);
$stmt->close();
$conn->close();
?>
