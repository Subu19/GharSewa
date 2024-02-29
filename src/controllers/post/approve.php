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
        $sql = "UPDATE worker set approved = 1 where worker_id = :wid;";
        $statement = $pdo->prepare($sql);
        $statement->bindParam(":wid", $_POST['wid']);
        if ($statement->execute()) {
            header("Location: /dashboard/pending");
            exit();
        } else {
            echo "Couldnt approve the user!";
        }
    } else {
        header("Location: /");
    }
}
