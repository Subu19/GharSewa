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
    $sql = "SELECT username,profilePic FROM user where user_id = :userid";

    $statement = $pdo->prepare($sql);
    $statement->bindParam(":userid", $userid);
    $statement->execute();

    $user = $statement->fetch(PDO::FETCH_ASSOC);

    $sql2 = "SELECT first_name,last_name,service_type,profilePic,username,user.user_id from worker INNER JOIN user on user.user_id = worker.user_id where approved = 0;";
    $statement2 = $pdo->prepare($sql2);
    $statement2->execute();
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pending Requests</title>
    <link rel="stylesheet" href="/src/css/navbar.css">
    <link rel="stylesheet" href="/src/css/main.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="/src/css/dashboard/main.css">
    <link rel="stylesheet" href="/src/css/dashboard/pending.css">

</head>

<body>
    <div class="navbar">
        <div class="wrapper">
            <div class="logoContainner">
                <img class="logo" id="logo" src="/assets/svgs/logoWide.svg" alt="" />
            </div>
            <div class="user">
                <div class="userContainner">
                    <i class="material-icons notification">notifications</i>
                    <img src="http://localhost:3000/<?php echo $user['profilePic'] ?>" class="navProfilePic"></img>
                </div>
            </div>
        </div>
    </div>
    <div class="wrapper">
        <div class="userBanner">
            <img src="http://localhost:3000/<?php echo $user['profilePic'] ?>" class="userpfp" alt="">
            <h2>Hi <?php echo $user['username'] ?>!, This is your dashboard!</h2>
        </div>
        <div class="hcontainner dashContainner">
            <div class="left">
                <div class="dashNavs">
                    <a class="dashNav" href="/dashboard">
                        <i class="material-icons">dashboard</i>
                        Dashboard
                    </a>
                    <a class="dashNav" href="/dashboard/workers">
                        <i class="material-icons">work</i>
                        Workers
                    </a>
                    <a class="dashNav selected" href="/dashboard/pending">
                        <i class="material-icons">perm_identity</i>
                        Pending Requests
                    </a>
                    <hr>
                    <a class="dashNav" href="/dashboard/addadmin">
                        <i class="material-icons">group_add</i>
                        Add Admin
                    </a>

                </div>
            </div>
            <div class="right">
                <?php if (isset($_GET["id"])) : ?>
                    <?php
                    $workersql = "SELECT * from worker INNER JOIN user on user.user_id = worker.user_id where user.user_id = :id ;";
                    $stat = $pdo->prepare($workersql);
                    $stat->bindParam(":id", $_GET["id"]);
                    $stat->execute();
                    $worker = $stat->fetch(PDO::FETCH_ASSOC);

                    $documentsql = "SELECT * from documents where user_id = :id;";
                    $stat1 = $pdo->prepare($documentsql);
                    $stat1->bindParam(":id", $_GET["id"]);
                    $stat1->execute();

                    ?>

                    <div class=" userdetails">
                        <img src="http://localhost:3000/<?php echo $worker['profilePic'] ?>" alt="" class="userImage2">
                        <div class="break"></div>
                        <div class="serviceType st-big">
                            <?php echo $worker['service_type'] ?>
                        </div>
                        <div class="break"></div>

                        <div class="detail">
                            <div class="title">Fist Name:</div>
                            <input class="input" type="text" value="<?php echo $worker["first_name"] ?>" disabled>
                        </div>
                        <div class="detail">
                            <div class="title">Last Name:</div>
                            <input class="input" type="text" value="<?php echo $worker["last_name"] ?>" disabled>
                        </div>
                        <div class="break"></div>


                        <div class="detail">
                            <div class="title">Username:</div>
                            <input class="input" type="text" value="<?php echo $worker["username"] ?>" disabled>
                        </div>
                        <div class="break"></div>
                        <div class="detail-wide">
                            <div class="title">About Me:</div>
                            <textarea class="textarea" disabled><?php echo $worker["description"] ?></textarea>
                        </div>
                        <div class="break"></div>
                        <?php while ($document = $stat1->fetch(PDO::FETCH_ASSOC)) : ?>

                            <?php if ($document['type'] === 'CITIZENSHIP_FRONT') : ?>
                                <div class="detail">
                                    <div class="title">Citizenship Front:</div>
                                    <img src="http://localhost:3000/<?php echo $document['url'] ?>" alt="" class="document">
                                </div>
                            <?php endif; ?>
                            <?php if ($document['type'] === 'CITIZENSHIP_BACK') : ?>
                                <div class="detail">
                                    <div class="title">Citizenship Back:</div>
                                    <img src="http://localhost:3000/<?php echo $document['url'] ?>" alt="" class="document">
                                </div>
                            <?php endif; ?>
                            <?php if ($document['type'] === 'RESUME') : ?>
                                <div class="detail">
                                    <a href="http://localhost:3000/<?php echo $document['url'] ?>"><button class="btn">View Resume</button></a>
                                </div>
                            <?php endif; ?>

                            <?php if ($document['type'] === 'CERTIFICATE') : ?>
                                <div class="detail">
                                    <div class="title">Certificate/Achivement:</div>
                                    <img src="http://localhost:3000/<?php echo $document['url'] ?>" alt="" class="document">
                                </div>
                            <?php endif; ?>

                        <?php endwhile; ?>
                        <div class="break"></div>
                        <div class="buttons">
                            <form action="/postreject" method="post">
                                <input type="text" hidden name="wid" value="<?php echo $worker["worker_id"] ?>">
                                <input type="text" hidden name="uid" value="<?php echo $worker["user_id"] ?>">

                                <button class="reject button2">
                                    Reject
                                </button>
                            </form>
                            <form action="/postapprove" method="post">
                                <input type="text" hidden name="wid" value="<?php echo $worker["worker_id"] ?>">
                                <input type="text" hidden name="uid" value="<?php echo $worker["user_id"] ?>">
                                <button class="approve button2">
                                    Approve
                                </button>
                            </form>

                        </div>
                    </div>






                <?php else : ?>
                    <div class=" pendingContainner">
                        <?php while ($row = $statement2->fetch(PDO::FETCH_ASSOC)) : ?>
                            <div class="userbox">
                                <img src="http://localhost:3000/<?php echo $row['profilePic'] ?>" alt="" class="userImage">
                                <div class="fullname">
                                    <?php echo $row["first_name"] ?> <?php echo $row["last_name"] ?>
                                </div>
                                <div class="username">
                                    <?php echo $row["username"] ?>
                                </div>
                                <div class="serviceType">
                                    <?php echo $row["service_type"] ?>
                                </div>
                                <a class="button" href="/dashboard/pending?id=<?php echo $row['user_id'] ?>">
                                    <button class="btn">View</button>
                                </a>

                            </div>
                        <?php endwhile; ?>
                    </div>


                <?php endif; ?>
            </div>
        </div>
    </div>

</body>

</html>