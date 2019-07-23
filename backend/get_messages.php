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
    $stmt = $conn->prepare("SELECT `message_id` FROM friends WHERE user_id = ? AND friend_id = ? AND unfriended = false");
    $stmt->bindValue(1, $_SESSION['user_id']);
    $stmt->bindValue(2, $_REQUEST['q']);
    $stmt->execute();
    $stmt->setFetchMode(PDO::FETCH_ASSOC);
    $records = $stmt->fetchAll();
    if (count($records) == 0) {
        echo json_encode(array());
    }
    $message_table_name = $records[0]['message_id'];

    // Fetch Messages
    $stmt = $stmt = $conn->query("SELECT * FROM $message_table_name");
    $stmt->execute();
    $stmt->setFetchMode(PDO::FETCH_ASSOC);
    $records = $stmt->fetchAll();
    $result = array();
    foreach ($records as $record) {
        array_push($result, $record);
    }
    $conn = null;
    echo json_encode($result);
} catch (PDOException $err) {
    echo json_encode($err->getMessage());
}