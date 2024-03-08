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

if (isset($_POST['friendid'])) {
    $sql = "SELECT * FROM (SELECT * FROM message WHERE (sender = :uid1 OR sender = :uid2) AND (receiver = :uid1 OR receiver = :uid2) ORDER BY time DESC LIMIT 10) AS subquery ORDER BY time ASC;";
    $friendid = $_POST['friendid'];
    $messages = $pdo->prepare($sql);
    $messages->bindParam(":uid1", $userid);
    $messages->bindParam(":uid2", $friendid);

    if ($messages->execute()) {
        $jsonMessages = json_encode($messages->fetchAll());
        echo $jsonMessages;
        http_response_code(200);

        $sql = "UPDATE message set isRead = 1 WHERE receiver = :uid and sender = :fid and isRead=0";
        $update = $pdo->prepare($sql);
        $update->bindParam(":uid", $userid);
        $update->bindParam(":fid", $friendid);
        $update->execute();
    } else {
        echo "Could'nt get the messages!";
        http_response_code(404);
    }
} else {
    echo "Friend Id not found!";
    http_response_code(404);
}
