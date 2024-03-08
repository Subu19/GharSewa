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
    $sql = "SELECT username,profilePic,isAdmin,isWorker,map_lat,map_lon FROM user where user_id = :userid";

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
    <link rel="stylesheet" href="/src/css/dashboard/profile.css">
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
                    <a class="dashNav selected" href="/dashboard/profile">
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
                        <a class="dashNav" href="/dashboard/requests">
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
                <form action="/update-profile" method="post" class="profileContainner">
                    <div class="detail">
                        <div class="title bold">Your Location:</div>

                        <div class="coordinates">
                            <div class="position">
                                <div class="title">
                                    Latitude:
                                </div>
                                <input id="latitude" value="<?php echo $user['map_lat'] ?>" name="latitude" type="text" class="input">
                            </div>
                            <div class="position">
                                <div class="title">
                                    Longitude:
                                </div>
                                <input id="longitude" value="<?php echo $user['map_lon'] ?>" name="longitude" type="text" class="input">
                            </div>
                        </div>
                        <!--The div element for the map -->
                        <div id="map"></div>

                        <button type="button" id="locationbtn" class="btn" onclick="setLocation()">Set Current Location</button> <button type="button" id="locationbtn" class="btn-none" onclick="viewMap()">View Map</button>
                    </div>
                    <div class="detail-wide">
                        <button type="submit" class="btn-primary">Update Profile</button>
                    </div>

                </form>
            </div>
        </div>
    </div>
    <script src="/src/js/main.js"></script>

    <script src="/src/js/dashboard/profile.js"></script>
</body>

</html>