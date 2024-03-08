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

    if (!$user['isWorker']) {
        echo "You are not a worker!";
        exit();
    }

    $wsql = "SELECT worker_id from worker where user_id = :uid;";
    $wstatment = $pdo->prepare($wsql);
    $wstatment->bindParam(":uid", $userid);
    $wstatment->execute();
    $worker = $wstatment->fetch(PDO::FETCH_ASSOC);


    $rsql = "SELECT profilePic, order_id,username,first_name, last_name,description,phone,map_lat,map_lon from orders inner join user on user.user_id = orders.user_id where status = 'Active' and worker_id = :wid;";
    $rstmnt = $pdo->prepare($rsql);
    $rstmnt->bindParam(":wid", $worker['worker_id']);
    $rstmnt->execute();
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
    <link rel="stylesheet" href="/src/css/dashboard/request.css">
    <?php include "src/views/components/mapApi.php" ?>
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
                        <a class="dashNav selected" href="/dashboard/active">
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
                <div class="requestContainner">
                    <?php
                    while ($request = $rstmnt->fetch(PDO::FETCH_ASSOC)) :
                    ?>
                        <div class="request">
                            <div class="userDetails">
                                <img src="http://localhost:3000/<?php echo $request['profilePic'] ?>" alt="" class="profilePic">
                                <div class="username">
                                    @<?php echo $request['username'] ?>
                                </div>
                                <div class="userInfo name">
                                    <?php echo $request['first_name'] ?> <?php echo $request['last_name'] ?>
                                </div>
                                <div class="userInfo">
                                    +977 <?php echo $request['phone'] ?>
                                </div>
                                <button type="button" class="btn-none" onclick="showMap(<?php echo $request['map_lat'] ?>,<?php echo $request['map_lon'] ?>)">View Address</button>
                            </div>
                            <div class="requestInfo">
                                <textarea class="textarea" disabled><?php echo $request['description'] ?> </textarea>
                                <div class="buttons">
                                    <form action="/postWorkComplete" method="post">
                                        <input type="text" name="order_id" value="<?php echo $request['order_id'] ?>" hidden>
                                        <button class="btn accept">Mark As Complete</button>
                                    </form>

                                </div>
                            </div>
                        </div>
                    <?php
                    endwhile;
                    ?>
                </div>
                <div id="mapContainner" class="mapContainner">
                    <div class="closeMap" onclick="closeMap()">❌</div>
                    <div id="map"></div>
                </div>

            </div>
        </div>
    </div>
    <script src="/src/js/map.js"></script>
    <script src="/src/js/main.js"></script>

    <script src="/src/js/dashboard/request.js"></script>
</body>

</html>