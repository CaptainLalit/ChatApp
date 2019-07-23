<?php

session_start();
if (!isset($_SESSION['logged_in'])) {
    session_destroy();
    header('location: ../frontend/login.php');
}

include "utilities/db_details.php";

try {

    $conn = new PDO("mysql:host=$db_servername;dbname=$db_name", $db_username, $db_password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // Find Message Table Name
    $stmt = $conn->prepare("SELECT `message_id` FROM friends WHERE user_id = ? AND friend_id = ?");
    $stmt->bindValue(1, $_SESSION['user_id']);
    $stmt->bindValue(2, $_POST['friend_id']);
    $stmt->execute();
    $stmt->setFetchMode(PDO::FETCH_ASSOC);
    $records = $stmt->fetchAll();
    $message_table_name = $records[0]['message_id'];

    // Fetch Messages
    $currentTime = date("Y-m-d H:i:s");
    $stmt = $conn->query("INSERT INTO $message_table_name VALUES ('$_POST[message]', '$_SESSION[user_id]', '$currentTime')");
    // change the last_active column in friends table
    $stmt = $conn->prepare("UPDATE friends SET last_active = ? WHERE message_id = ?");
    $stmt->bindValue(1, $currentTime);
    $stmt->bindValue(2, $message_table_name);
    $stmt->execute();
    $conn = null;
    echo "Message Inserted";
} catch(PDOException $err) {
    echo $err->getMessage();
}