<?php
session_start();
if (!isset($_SESSION['user_id']) || !isset($_SESSION['username'])) {
    // Redirect unauthorized users to the login page
    header("location: /login");
    exit();
} else {

    require_once("src/database/connect.php");

    $userid = $_SESSION['user_id'];
    $username = $_SESSION['username'];
}


if (isset($_GET['id'])) {
    $notification_id = filter_var($_GET['id'], FILTER_VALIDATE_INT);
    if ($notification_id !== false) {
        //mark as read
        $sql = "UPDATE notification SET notification.read = 1 WHERE nid = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id', $notification_id);
        $stmt->execute();
        //fetch link and redirect
        $sql = "SELECT link FROM notification WHERE nid = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id', $notification_id);
        $stmt->execute();
        $notification = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($notification && isset($notification['link'])) {
            header("Location: " . $notification['link']);
            exit();
        }
    }
}

header("Location: /");
exit();
