<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);
    $gender = isset($_POST['gender']) ? $_POST['gender'] : '';


    if (empty($username) || empty($password) || empty($confirm_password)) {
        die("Please fill in all fields.");
    }

    if ($password !== $confirm_password) {
        die("Passwords do not match.");
    }


    $userData = "$username|$password|$gender\n"; 


    $filePath = 'users.txt';


    if (file_put_contents($filePath, $userData, FILE_APPEND | LOCK_EX) === false) {
        die("Error saving user data.");
    }
    $_SESSION['registration_success'] = "Registration successful! You can now log in.";
    header('Location: index.php');
    exit;
}
?>