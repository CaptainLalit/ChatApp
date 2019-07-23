<?php

session_start();
if (!isset($_SESSION['logged_in'])) {
    session_destroy();
    header('location: ../frontend/login.php');
}

include "utilities/db_details.php";

try {
    $conn = new PDO("mysql:host=$db_servername;dbname=$db_name", $db_username, $db_password);
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $conn->prepare("SELECT full_name, is_active, last_active FROM users WHERE user_id = :user_id");
    $stmt->bindParam(":user_id", $_REQUEST['q']);
    $stmt->execute();

    // set the resulting array to associative
    $stmt->setFetchMode(PDO::FETCH_ASSOC);
    $records = $stmt->fetchAll();
    $result = array();
    foreach ($records as $record) {
        array_push($result, $record);
    }
    // close the connection
    $conn = null;
    echo json_encode($result);
} catch (PDOException $e) {
    echo json_encode(array($e->getMessage()));
}