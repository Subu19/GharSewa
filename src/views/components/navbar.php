<?php
session_start();
if (!isset($_SESSION['user_id']) || !isset($_SESSION['username'])) {
    // Redirect unauthorized users to the login page
} else {

    require_once("src/database/connect.php");

    $userid = $_SESSION['user_id'];
    $username = $_SESSION['username'];
    $sql = "SELECT username,profilePic,isAdmin,isWorker,map_lon,map_lat FROM user where user_id = :userid";

    $statement = $pdo->prepare($sql);
    $statement->bindParam(":userid", $userid);
    $statement->execute();

    $user = $statement->fetch(PDO::FETCH_ASSOC);

    //get notifications
    $notifysql = "SELECT * from notification where user_id = :uid  order by time desc limit 10;";
    $notifystat = $pdo->prepare($notifysql);
    $notifystat->bindParam(":uid", $userid);
    $notifystat->execute();

    $unreadCount = 0;
    $notifications = $notifystat->fetchAll();

    foreach ($notifications as $n) {
        if (!$n['read']) {
            $unreadCount++;
        }
    }

    //get unread messages
    $sql = "SELECT count(*) as unread from message where isRead=0 and receiver = :uid;";
    $statement = $pdo->prepare($sql);
    $statement->bindParam(":uid", $userid);
    $statement->execute();
    $unread = $statement->fetch(PDO::FETCH_ASSOC);

    if ($unread['unread'] != 0) {
        $unreadCount += $unread['unread'];
    }
}


?>

<div class="mobileNavbar hideNavBar">
    <div class="mobile-navs">
        <div class="mobile-nav">Home</div>
        <div class="mobile-nav">Services</div>
        <div class="mobile-nav">Our Location</div>
        <div class="mobile-nav">About Us</div>
    </div>
</div>
<div class="navbar" id="navbar">
    <div class="wrapper">
        <div class="logoContainner">
            <img class="logo" id="logo" src="./assets/svgs/logoWide.svg" alt="" />
        </div>
        <div class="navs">
            <a href="/">
                <div class="nav" id="homeNav">Home</div>
            </a>
            <a href="/services">
                <div class="nav" id="serviceNav">Services</div>
            </a>
            <a href="/locations" class=" nav" id="locationNav">Our Locations</a>
            <div class="nav" id="aboutNav">About Us</div>
            <div class="user">
                <?php if (isset($user)) : ?>

                    <div class="userContainner">
                        <div class="notify">
                            <div class="unread <?php echo $unreadCount > 0 ? "" : "n-hide" ?>"><?php echo $unreadCount ?></div>
                            <i onclick="document.getElementById('notificationPopup').classList.toggle('hideNotificationMenu')" class="material-icons notification" id="notification">notifications</i>
                        </div>
                        <img onclick="document.getElementById('profileMenu').classList.toggle('hideProfileMenu')" src="http://localhost:3000/<?php echo $user['profilePic'] ?>" id="navProfilePic" class="navProfilePic"></img>

                        <!-- //notification popup   -->
                        <div class="notificationPopup hideNotificationMenu" id="notificationPopup">
                            <div class="n-title notificationtitle">Notifications</div>
                            <div class="notifications">
                                <?php if ($unread['unread'] > 0) : ?>
                                    <a class="notificationbox <?php echo $notification['read'] ? "" : "new" ?>" href="/dashboard/messages">
                                        <div class="n-heading">
                                            <div class="n-title "><?php echo $unread['unread'] ?> New Messages!</div>
                                        </div>
                                        <div class="n-subject">
                                            Check your Dashboard! You have new Messages waiting for you.
                                        </div>
                                    </a>
                                <?php endif; ?>
                                <?php foreach ($notifications as $notification) : ?>
                                    <a class="notificationbox <?php echo $notification['read'] ? "" : "new" ?>" href="/redirect-notification?id=<?php echo $notification['nid'] ?>">
                                        <div class="n-heading">
                                            <div class="n-title "><?php echo $notification['title'] ?></div>
                                            <div class="n-time"><?php echo $notification['time'] ?></div>
                                        </div>
                                        <div class="n-subject">
                                            <?php echo $notification['message'] ?>
                                        </div>
                                    </a>
                                <?php endforeach; ?>
                            </div>
                        </div>


                        <!-- //use menu pop up  -->
                        <div class="profileMenu hideProfileMenu" id="profileMenu">
                            <div class="profileTag">
                                @<?php echo $user['username'] ?>
                            </div>
                            <hr>
                            <a class="pmenu" href="/dashboard">
                                <i class="material-icons">dashboard</i>
                                Dashboard
                            </a>
                            <a class="pmenu" href="/dashboard/profile">
                                <i class="material-icons">account_box</i>
                                My Profile
                            </a>
                            <a class="pmenu" href="/dashboard/messages">
                                <i class="material-icons">message</i>
                                Messages
                            </a>
                            <?php if ($user['isAdmin']) : ?>
                                <hr>
                                <a class="pmenu" href="/admin/dashboard">
                                    <i class="material-icons">developer_board</i>
                                    Admin Dashboard
                                </a>
                            <?php endif; ?>
                            <hr>
                            <a class="pmenu logout" href="/logout">
                                <i class="material-icons">logout</i>
                                Logout
                            </a>
                        </div>
                    </div>
                    <script>
                        document.addEventListener("click", () => {
                            const element = document.getElementById('profileMenu');
                            const element2 = document.getElementById('notificationPopup');
                            if (!element.classList.contains("hideProfileMenu") && !event.target.matches("#navProfilePic")) {
                                element.classList.add("hideProfileMenu");
                            }
                            if (!element2.classList.contains("hideNotificationMenu") && !event.target.matches("#notification")) {
                                element2.classList.add("hideNotificationMenu");
                            }
                        });
                    </script>
                <?php else : ?>

                    <a class="login" href="/login">

                        <img src="./assets/images/default.png" alt="" />
                        <div>Login</div>
                    </a>

                <?php endif; ?>

            </div>
        </div>
        <div class="hamburger">
            <input type="checkbox" class="checkbox" onclick="toggleNavBar()" />
            <svg width="50" height="50" viewBox="0 0 50 50" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M6 11L44 11" stroke="black" stroke-width="4" stroke-linecap="round" class="lineTop line" />
                <path class="lineMid line" d="M6 24H43" stroke="black" stroke-width="4" stroke-linecap="round" />
                <path class="lineBottom line" d="M6 37H43" stroke="black" stroke-width="4" stroke-linecap="round" />
            </svg>
        </div>
    </div>

</div>