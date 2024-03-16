<?php
require_once "src/database/connect.php";

if (isset($_GET['username'])) {
    $sql = "SELECT username from user where username = :username";
    $statement = $pdo->prepare($sql);
    $statement->bindParam(":username", $_GET['username']);
    $statement->execute();
    $users = $statement->fetchAll(PDO::FETCH_ASSOC);

    if (!$users) {
        echo "safe";
    } else {
        echo "matched";
    }
}
