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
    $lat = isset($_POST['latitude']) ? $_POST['latitude'] : "0";
    $lng = isset($_POST['longitude']) ? $_POST['longitude'] : "0";

    $sql = "UPDATE user SET map_lat = :lat, map_lon = :lon where user_id = :uid;";
    $stmnt = $pdo->prepare($sql);
    $stmnt->bindParam(":lat", $lat);
    $stmnt->bindParam(":lon", $lng);
    $stmnt->bindParam(":uid", $userid);
    if ($stmnt->execute()) {
        header("Location: /dashboard/profile");
    } else {
        echo "error";
        exit();
    }
}
