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

if (isset($_POST['friendId'])) {
    $sql = "INSERT into message(sender,receiver,message,time) VALUES(:sender,:receiver,:message,:time);";
    $statement = $pdo->prepare($sql);
    $sender = $userid;
    $receiver = $_POST['friendId'];
    $message = $_POST['message'];
    $time = round(microtime(true) * 1000);
    $statement->bindParam(":sender", $sender);
    $statement->bindParam(":receiver", $receiver);
    $statement->bindParam(":message", $message);
    $statement->bindParam(":time", $time);
    if ($statement->execute()) {
        echo "Sent";
        http_response_code(200);
    } else {
        echo "Could'nt save the message!";
        http_response_code(404);
    }
} else {
    echo "Friend Id not found!";
    http_response_code(404);
}
