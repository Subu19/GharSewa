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
    $sql = "SELECT worker_id,username,profilePic,isAdmin,isWorker FROM user inner join worker on worker.user_id = user.user_id  where user.user_id = :userid";

    $statement = $pdo->prepare($sql);
    $statement->bindParam(":userid", $userid);
    $statement->execute();

    //fetch user details
    $user = $statement->fetch(PDO::FETCH_ASSOC);

    if (!$user['isWorker']) {
        echo "You are not authorized to access this!";
        exit();
    }
}

if ($_SERVER['REQUEST_METHOD'] === "POST") {
    if (isset($_POST['order_id'])) {
        $sql = "DELETE from orders where order_id = :oid and worker_id = :wid and status = 'Pending';";
        $statement = $pdo->prepare($sql);
        $statement->bindParam(":oid", $_POST['order_id']);
        $statement->bindParam(":wid", $user['worker_id']);
        sendNotification($pdo, getUserFromOrder($pdo, $_POST['order_id']), "Rejected!", "Your request was rejected by the worker! Please try someone else.", "/");
        if ($statement->execute()) {
            header("Location: /dashboard/requests");
            exit();
        } else {
            echo "Couldnt reject the user!";
            exit();
        }
    } else {
        header("Location: /");
    }
}
