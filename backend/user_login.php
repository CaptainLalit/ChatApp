<?php
// Using dummy user
// Find user in database
$servername = "localhost";
$username = "lalit";
$password = "Surender@25";
$db = "chatapp";
session_start();

try {
    $conn = new PDO("mysql:host=$servername;dbname=$db", $username, $password);
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $conn->prepare("SELECT user_id, password FROM users WHERE user_id = :user_id");
    $stmt->bindParam(":user_id", $_POST['user_id']);
    $stmt->execute();

    // set the resulting array to associative
    $stmt->setFetchMode(PDO::FETCH_ASSOC);
    $result = $stmt->fetchAll();
    if (count($result) > 0) {
        if ($result[0]['password'] === $_POST['password']) {
            session_destroy(); // clear the session
            session_start(); // restart the session
            $_SESSION['logged_in'] = true;
            $_SESSION['user_id'] = $_POST['user_id'];
            header('location: ../frontend/dashboard.php');
        } else {
            $_SESSION['user_password_error'] = $_POST['user_id'];
            header('location: ../frontend/login.php');
        }
    } else {
        $_SESSION['user_notfound_error'] = true;
        header('location: ../frontend/login.php');
    }

    // close the connection
    $conn = null;
} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}