<?php
    session_start();
    if (!isset($_SESSION['logged_in'])) {
        session_destroy();
        header('location: ../frontend/login.php');
    }

    include "utilities/db_details.php";

    try {
        $conn = new PDO("mysql:host=$db_servername;dbname=$db_name", $db_username, $db_password);
        $stmt = $conn->prepare("SELECT f.friend_id, f.hide_status, u.full_name, u.last_active, u.is_active, f.unfriended, u.hide_status_to_all FROM friends as f INNER JOIN users as u ON u.user_id = f.friend_id WHERE f.user_id = ? AND f.unfriended = false ORDER BY f.last_active DESC");
        $stmt->bindValue(1, $_SESSION['user_id']);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $records = $stmt->fetchAll();
        $result = array();
        foreach ($records as $record) {
            array_push($result, $record);
        }
        $conn = null;
        echo json_encode($result);
    } catch (PDOException $err) { }