<?php
require 'db.php';
session_start();

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $email = $_POST['email'];
    $password = $_POST['password'];
    if(!empty($email) && !empty($password)){
        $stmt = $conn->prepare("SELECT userid,name,email,password FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();
        if($stmt->num_rows >0){
            $stmt->bind_result($userid,$name,$email,$hashed_password);
            $stmt->fetch();
            if(password_verify($password,$hashed_password)){
                $_SESSION['userid']= $userid;
                $_SESSION['useremail'] = $email;
                $_SESSION['username'] = $name;
                header("Location: ../frontend/userList.html");
                exit();
            }else{
                echo "Invalid password. Please Try again";
            }
        }else{
            echo "No user found with that email address.";
        }
        $stmt->close();
    }
}
$conn->close();

?>