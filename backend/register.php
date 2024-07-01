<?php
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
$name = $_POST['name'];
$email = $_POST['email'];
$password = $_POST['password'];
if(!empty($name) && !empty($email) && !empty($password)){

    $stmt = $conn->prepare("SELECT userid FROM users WHERE email = ?");
    $stmt->bind_param("s",$email);
    $stmt->execute();
    $stmt->store_result();
    if($stmt->num_rows >0){
        echo "Email is already registered";
        $stmt->close();    
    }else{
        $stmt->close();
        $hashed_password = password_hash($password,PASSWORD_DEFAULT);


        $stmt = $conn->prepare("INSERT INTO users (name,email,password) VALUES (?,?,?)");
        $stmt->bind_param("sss",$name,$email,$hashed_password);
        if($stmt->execute()){
            $_SESSION['user'] = $email;
            header('Location: /login.html');
            exit();
        }else{
            echo "Error: " . $stmt->error;
        }
        $stmt->close();
    }
}else{
    echo "Please fill in all fields.";

}
}
?>