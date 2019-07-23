<?php

session_start();
if (!isset($_SESSION['user_id'])) {
    header('location: ../frontend/home.php');
}



// Connect to database
$servername = "localhost";
$username = "lalit";
$password = "Surender@25";
$db = "chatapp";

try {
    // Connect to database
    $conn = new PDO("mysql:host=$servername;dbname=$db", $username, $password);
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // set is_active true and set last_active time to current time in users table
    $currentTime = date("Y-m-d H:i:s");
    $stmt = $conn->prepare("UPDATE users SET is_active = false, last_active = :time WHERE user_id = :user_id");
    $stmt->bindParam(':time', $currentTime);
    $stmt->bindParam(':user_id', $_SESSION['user_id']);
    $stmt->execute();
    // close the connection
    $conn = null;
} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}

session_destroy();
// Redirect to login page
header('location: ../frontend/login.php');