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
    $sql = "SELECT username,profilePic,isAdmin,isWorker FROM user where user_id = :userid";

    $statement = $pdo->prepare($sql);
    $statement->bindParam(":userid", $userid);
    $statement->execute();


    $user = $statement->fetch(PDO::FETCH_ASSOC);
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="/src/css/navbar.css">
    <link rel="stylesheet" href="/src/css/main.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="/src/css/dashboard/main.css">
</head>

<body>
    <?php include "./src/views/components/navbarDashboard.php" ?>

    <div class="wrapper">

        <div class="hcontainner dashContainner">
            <div class="left">
                <div class="dashNavs">

                    <a class="dashNav" href="/dashboard/messages">
                        <i class="material-icons">message</i>
                        Messages
                    </a>
                    <a class="dashNav" href="/dashboard/notifications">
                        <i class="material-icons">notifications</i>
                        Notifications
                    </a>
                    <hr>
                    <a class="dashNav " href="/dashboard/profile">
                        <i class="material-icons">account_box</i>
                        My Profile
                    </a>
                    <a class="dashNav" href="/dashboard/security">
                        <i class="material-icons">security</i>
                        Security
                    </a>

                    <?php if ($user['isWorker']) : ?>
                        <hr>
                        <a class="dashNav" href="/dashboard">
                            <i class="material-icons">dashboard</i>
                            Dashboard
                        </a>
                        <a class="dashNav " href="/dashboard/requests">
                            <i class="material-icons">warning</i>
                            Requests
                        </a>
                        <a class="dashNav" href="/dashboard/active">
                            <i class="material-icons">label</i>
                            Active
                        </a>

                        <a class="dashNav" href="/dashboard/workprofile">
                            <i class="material-icons">account_circle</i>
                            My Pro Profile
                        </a>
                    <?php endif; ?>
                </div>
            </div>
            <div class="right">
                This is your dashoboard! Coming more....
            </div>
        </div>
    </div>
    <script src="/src/js/main.js"></script>

</body>

</html>