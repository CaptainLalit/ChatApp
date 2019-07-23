<?php
    // If user is not logged in then redirect to login page
    session_start();
    if (!isset($_SESSION['logged_in'])) {
        session_destroy();
        header('location: ../frontend/login.php');
    }

    // including database_details
    include "utilities/db_details.php";

    // Connect to database and find users for the given term, search in (name, user_id)
    try {
        $conn = new PDO("mysql:host=$db_servername;dbname=$db_name", $db_username, $db_password);
        // No need to set the PDO error mode to exception

        // create query and execute
        $stmt = $conn->prepare("SELECT user_id, full_name FROM users WHERE (full_name LIKE ? OR user_id LIKE ?) AND user_id NOT IN (SELECT friend_id FROM friends WHERE user_id = ? and unfriended = false) AND user_id != ? LIMIT 100");
        $stmt->bindValue(1, "%$_REQUEST[q]%");
        $stmt->bindValue(2, "%$_REQUEST[q]%");
        $stmt->bindValue(3, $_SESSION['user_id']);
        $stmt->bindValue(4, $_SESSION['user_id']);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $records = $stmt->fetchAll();
        $result = array();
        foreach ($records as $record) {
            array_push($result, $record);
        }
        // close the connection
        $conn = null;

        // send the json encoded data
        echo json_encode($result);
    } catch (PDOException $err) { }