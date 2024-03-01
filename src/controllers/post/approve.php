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
        //approve the worker
        $sql = "UPDATE worker set approved = 1 where worker_id = :wid;";
        $statement = $pdo->prepare($sql);
        $statement->bindParam(":wid", $_POST['wid']);

        if ($statement->execute()) {
            //set isworker for the user
            $sql3 = "SELECT user_id from worker where worker_id = :wid;";
            $statement3 = $pdo->prepare($sql3);
            $statement3->bindParam(":wid", $_POST['wid']);
            $statement3->execute();

            $worker = $statement3->fetch(PDO::FETCH_ASSOC);
            $sql2 = "UPDATE user set isWorker = 1 where user_id = :uid;";
            $statement2 = $pdo->prepare($sql2);
            $statement2->bindParam(":uid", $worker['user_id']);

            if ($statement2->execute()) {
                //set default working days for the worker!
                $sql4 = "INSERT INTO working_days(worker_id) values(:wid);";
                $statement4 = $pdo->prepare($sql4);
                $statement4->bindParam(":wid", $_POST['wid']);

                if ($statement4->execute()) {
                    header("Location: /admin/dashboard/pending");
                    exit();
                } else {
                    echo "Could'nt set working days!";
                    exit();
                }
            } else {
                echo "Could'nt set isWorker";
                exit();
            }
        } else {
            echo "Couldnt approve the user!";
            exit();
        }
    } else {
        header("Location: /");
        exit();
    }
}
