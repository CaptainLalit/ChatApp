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
    // UnFriend
    $stmt = $conn->prepare("UPDATE friends SET unfriended = true WHERE user_id = ? AND friend_id = ?");
    $stmt->bindValue(1, $_SESSION['user_id']);
    $stmt->bindValue(2, $_REQUEST['q']);
    $stmt->execute();
    $stmt = $conn->prepare("UPDATE friends SET hide_status = true WHERE user_id = ? AND friend_id = ?");
    $stmt->bindValue(2, $_SESSION['user_id']);
    $stmt->bindValue(1, $_REQUEST['q']);
    $stmt->execute();
    $conn = null;
    echo "Friend Removed";
} catch(PDOException $e) {
    echo $e->getMessage();
}