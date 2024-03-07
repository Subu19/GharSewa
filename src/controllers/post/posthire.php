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
    $sql = "INSERT INTO orders(worker_id,user_id,`from`,`to`,status,description) VALUES(:wid,:uid,:from,:to,:status,:description);";
    $workerid = $_POST['worker_id'];
    $from = $_POST['from'];
    $to = $_POST['to'];
    $status = "Pending";
    $description = $_POST['description'];
    $statement = $pdo->prepare($sql);
    $statement->bindParam(":wid", $workerid);
    $statement->bindParam(":uid", $userid);
    $statement->bindParam(":from", $from);
    $statement->bindParam(":to", $to);
    $statement->bindParam(":status", $status);
    $statement->bindParam(":description", $description);
    if ($statement->execute()) {
        sendNotification($pdo, $userid, "Request Sent!", "Your request was sent. Please wait for the response from the worker!", "/worker?id=" . $workerid);
        sendNotification($pdo, getUserFromWorker($pdo, $workerid), "New Work Request!", "Please check your request section on diashboard for more information", "/dashboard/requests");
        header("Location: /worker?id=" . $workerid);
        exit();
    } else {
        echo "Something went wrong!";
        exit();
    }
}
