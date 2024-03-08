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
    $sql = "SELECT username,profilePic,isWorker,isAdmin FROM user where user_id = :userid";

    $statement = $pdo->prepare($sql);
    $statement->bindParam(":userid", $userid);
    $statement->execute();
    $user = $statement->fetch(PDO::FETCH_ASSOC);


    $stmt = $pdo->prepare("
        SELECT user_id, profilePic, first_name, last_name, username 
FROM (
    SELECT 
        CASE 
            WHEN user1 = :uid THEN user1 
            ELSE user2 
        END AS you,
        CASE 
            WHEN user2 = CASE 
                            WHEN user1 = :uid THEN user1 
                            ELSE user2 
                        END 
            THEN user1
            ELSE user2
        END AS other
    FROM (
        SELECT DISTINCT 
            LEAST(sender, receiver) AS user1, 
            GREATEST(sender, receiver) AS user2
        FROM message 
        WHERE sender = :uid OR receiver = :uid
    ) AS users
) AS inbox 
INNER JOIN user ON user.user_id = inbox.other
ORDER BY (SELECT MAX(time) FROM message WHERE (sender = inbox.you AND receiver = inbox.other) OR (sender = inbox.other AND receiver = inbox.you)) ASC;

    ");

    $stmt->bindParam(':uid', $userid);
    $stmt->execute();
    $inbox = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (isset($_GET['id'])) {
        $sql = "SELECT username,profilePic FROM user where user_id = :userid";
        $friendid = $_GET['id'];
        $statement = $pdo->prepare($sql);
        $statement->bindParam(":userid", $friendid);
        $statement->execute();
        $friend = $statement->fetch(PDO::FETCH_ASSOC);
        if (!$friend) {
            echo "Invalid url. Cant find the user id!";
            exit();
        }
        $sql = "SELECT * FROM (SELECT * FROM message WHERE (sender = :uid1 OR sender = :uid2) AND (receiver = :uid1 OR receiver = :uid2) ORDER BY time DESC LIMIT 10) AS subquery ORDER BY time ASC;";
        $messages = $pdo->prepare($sql);
        $messages->bindParam(":uid1", $userid);
        $messages->bindParam(":uid2", $friendid);
        $messages->execute();
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
    <link rel="stylesheet" href="/src/css/dashboard/messages.css">
    <?php echo "<script>var userid = {$userid};</script>" ?>

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
                <div class="dmContainner">
                    <div class="dmHeading">
                        <div class="title">Inbox</div>
                    </div>
                    <div class="dms">
                        <?php foreach ($inbox as $dm) : ?>
                            <a href="/dashboard/messages?id=<?php echo $dm['user_id'] ?>" class="dmUser <?php echo $friendid && $friendid == $dm['user_id'] ? "dmOpen" : "" ?> ?>">
                                <img class="msg-profile" src="/<?php echo $dm['profilePic'] ?>"></img>
                                <div class="name"><?php echo $dm['first_name'] . " " .  $dm['last_name'] ?></div>
                            </a>
                        <?php endforeach; ?>
                    </div>

                </div>
                <div class="messageContainner">
                    <?php if (isset($friend)) : ?>
                        <div class="messageHeading">
                            <img class="msg-profile" src="/<?php echo $friend['profilePic'] ?>"></img>
                            <div class="title"><?php echo $friend['username'] ?></div>
                        </div>
                        <div class="messageBox" id="messageBox">
                            <?php while ($message = $messages->fetch(PDO::FETCH_ASSOC)) : ?>
                                <div class="msg <?php echo $message['sender'] == $userid ? "me" : "other" ?>"><?php echo $message['message'] ?></div>
                            <?php endwhile; ?>
                        </div>
                        <form id="messageForm" class="messageFooter" method="post">
                            <input autocomplete="off" id="messageInput" name="message" type="text" class="input" autofocus>
                            <input id="friendid" type="text" hidden name="friendId" value="<?php echo $friendid ?>">
                            <button class="btn">Send</button>
                        </form>
                        <audio id="sound" src="/assets/message.mp3" hidden></audio>
                    <?php endif; ?>
                </div>

            </div>
        </div>
    </div>
    <script src="/src/js/main.js"></script>
    <script src="/src/js/dashboard/message.js"></script>

</body>

</html>