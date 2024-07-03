<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require 'db.php';
session_start();
if (!isset($_SESSION['userid'])) {
    die("User not logged in");
}

if(isset($_SESSION['userid'])){
    $current_user = $_SESSION['userid'];
    $stmt = $conn->prepare("SELECT userid,name FROM users WHERE userid != ?");
    $stmt->bind_param("i",$current_user);
    $stmt->execute();
    $result = $stmt->get_result();

    $users =[];
    while($row = $result->fetch_assoc()){
        $users[] = $row;
    }
    echo json_encode($users);
    $stmt->close();
    
} else{
    echo json_encode([]);

} 
$conn->close()

?>