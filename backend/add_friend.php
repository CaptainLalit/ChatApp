<?php
    session_start();
    // If user is not logged in then redirect to login page
    if (!isset($_SESSION['logged_in'])) {
        session_destroy();
        header('location: ../frontend/login.php');
    }

    include "utilities/dashboard_utils.php";
    include "utilities/db_details.php";

    $friendUserId = $_REQUEST['q'];

    try {
        $conn = new PDO("mysql:host=$db_servername;dbname=$db_name", $db_username, $db_password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        // If already friends but unfriended is true the set unfriended to false
        $stmt = $conn->prepare("SELECT `message_id` FROM friends WHERE user_id = ? AND friend_id = ? AND unfriended = true");
        $stmt->bindValue(1, $_SESSION['user_id']);
        $stmt->bindValue(2, $friendUserId);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $records = $stmt->fetchAll();
        if (count($records) > 0) {
            $stmt = $conn->prepare("UPDATE friends SET unfriended = false WHERE user_id = ? AND friend_id = ?");
            $stmt->bindValue(1, $_SESSION['user_id']);
            $stmt->bindValue(2, $friendUserId);
            $stmt->execute();
            $stmt = $conn->prepare("UPDATE friends SET hide_status = false WHERE user_id = ? AND friend_id = ?");
            $stmt->bindValue(2, $_SESSION['user_id']);
            $stmt->bindValue(1, $friendUserId);
            $stmt->execute();
            return;
        }

        // Insert values into friends Table
        $messageTableId = get_unique_table_id();
        $currentTime = date("Y-m-d H:i:s");
        $stmt = $conn->prepare("INSERT INTO friends VALUES (:user_id, :friend_id, :message_id, :time, false, false)");
        $stmt->bindParam(":user_id", $_SESSION['user_id']);
        $stmt->bindParam(":friend_id", $friendUserId);
        $stmt->bindParam(":message_id", $messageTableId);
        $stmt->bindParam(":time", $currentTime);
        $stmt->execute();
        $stmt = $conn->prepare("INSERT INTO friends VALUES (:user_id, :friend_id, :message_id, :time, false, false)");
        $stmt->bindParam(":user_id", $friendUserId);
        $stmt->bindParam(":friend_id", $_SESSION['user_id']);
        $stmt->bindParam(":message_id", $messageTableId);
        $stmt->bindParam(":time", $currentTime);
        $stmt->execute();
        // Create message table for them
        $stmt = "CREATE TABLE $messageTableId (message TEXT, sender_id VARCHAR(30) NOT NULL, posted_at DATETIME NOT NULL)";
        $conn->exec($stmt);
        // close the connection
        $conn = null;
        echo "Friend Added";
    } catch(PDOException $e) {
        echo $e->getMessage();
    }