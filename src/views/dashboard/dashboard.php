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
    $sql = "SELECT username,profilePic,isAdmin FROM user where user_id = :userid";

    $statement = $pdo->prepare($sql);
    $statement->bindParam(":userid", $userid);
    $statement->execute();


    $user = $statement->fetch(PDO::FETCH_ASSOC);
    if (!$user['isAdmin']) {
        echo "You are not authorized to access this!";
        exit();
    }
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
        <div class="userBanner">
            <img src="http://localhost:3000/<?php echo $user['profilePic'] ?>" class="userpfp" alt="">
            <h2 class="username">Hi <?php echo $user['username'] ?>!, This is your dashboard!</h2>
        </div>
        <div class="hcontainner dashContainner">
            <div class="left">
                <div class="dashNavs">
                    <a class="dashNav selected" href="/dashboard">
                        <i class="material-icons">dashboard</i>
                        Dashboard
                    </a>
                    <a class="dashNav" href="/admin/dashboard/workers">
                        <i class="material-icons">work</i>
                        Workers
                    </a>
                    <a class="dashNav " href="/admin/dashboard/pending">
                        <i class="material-icons">perm_identity</i>
                        Pending Requests
                    </a>
                    <hr>
                    <a class="dashNav" href="/admin/dashboard/addadmin">
                        <i class="material-icons">group_add</i>
                        Add Admin
                    </a>

                </div>
            </div>
            <div class="right">
                This is your dashoboard! Coming more....
            </div>
        </div>
    </div>

</body>

</html>