<?php

session_start();
if (!isset($_SESSION['registration_process'])) {
    session_destroy();
    header('location: ../frontend/register.php');
}

include "utilities/db_details.php";

$user_id = $_POST['user_id'];
$phone_no = isset($_POST['phone_no']) ? $_POST['phone_no'] : '';
$full_name = $_POST['full_name'];
$password = $_POST['password'];


try {
    $conn = new PDO("mysql:host=$db_servername;dbname=$db_name", $db_username, $db_password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // Create users and friends table if not exists
    $stmt = $conn->prepare("CREATE TABLE IF NOT EXISTS users(id INT(11) NOT NULL PRIMARY KEY AUTO_INCREMENT, full_name VARCHAR(50) NOT NULL, phone_no VARCHAR(20), user_id VARCHAR(50) NOT NULL UNIQUE , password VARCHAR(50) NOT NULL, last_active TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , is_active BOOLEAN NOT NULL DEFAULT false, hide_status_to_all BOOLEAN NOT NULL DEFAULT false )");
    $stmt->execute();
    $stmt = $conn->prepare("CREATE TABLE IF NOT EXISTS friends(user_id VARCHAR(50) NOT NULL, friend_id VARCHAR(50) NOT NULL, message_id VARCHAR(256) NOT NULL, last_active TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP, unfriended BOOLEAN NOT NULL DEFAULT false, hide_status BOOLEAN NOT NULL DEFAULT false )");
    $stmt->execute();

    // Check if the user with given email already exists or not
    $stmt = $conn->prepare("SELECT user_id FROM users WHERE user_id = :user_id");
    $stmt->bindParam(':user_id', $user_id);
    $stmt->execute();
    $stmt->setFetchMode(PDO::FETCH_ASSOC);
    if (count($stmt->fetchAll()) > 0) {
        $_SESSION['user_already_exists_error'] = true;
        $_SESSION['phone_no'] = $phone_no;
        $_SESSION['full_name'] = $full_name;
        $conn = null;
        header('location: ../frontend/register.php');
    }

    // Else enter the details of the user
    $currentTime = date('Y-m-d H:i:s');
    $stmt = $conn->prepare("INSERT INTO users (full_name, phone_no, user_id, password, last_active, is_active, hide_status_to_all) VALUES (:full_name, :phone_no, :user_id, :password, :time, false, false)");
    $stmt->bindParam(':full_name', $full_name);
    $stmt->bindParam(':phone_no', $phone_no);
    $stmt->bindParam(':user_id', $user_id);
    $stmt->bindParam(':password', $password);
    $stmt->bindParam(':time', $currentTime);
    $stmt->execute();
    // close the connection
    $conn = null;
    // Redirect to login page with user_id in session
    session_destroy(); // clear the session
    session_start(); // start the new session
    $_SESSION['user_id_registration_success'] = $user_id;
    header('location: ../frontend/login.php');
} catch (PDOException $err) {
    echo $err->getMessage();
}
