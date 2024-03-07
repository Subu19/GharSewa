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

if ($_SERVER['REQUEST_METHOD'] === "POST") {
    if (isset($_POST['wid'])) {
        $sql = "DELETE from worker where worker_id = :wid; DELETE from documents where user_id = :uid;";
        $statement = $pdo->prepare($sql);
        $statement->bindParam(":wid", $_POST['wid']);
        $statement->bindParam(":uid", $_POST['uid']);
        if ($statement->execute()) {
            sendNotification($pdo, $_POST['uid'], "Rejected :(", "Your work profile was Rejected! Try again with proper documents.", "/");
            sendNotification($pdo, getUserFromWorker($pdo, $_POST['wid']), "Rejected :(", "Your work profile was Rejected! Try again with proper documents.", "/");
            header("Location: /admin/dashboard/pending");
            exit();
        } else {
            echo "Couldnt reject the user!";
            exit();
        }
    } else {
        header("Location: /");
    }
}
