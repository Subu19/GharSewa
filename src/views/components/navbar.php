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
            <div class="nav" id="locationNav">Our Locations</div>
            <div class="nav" id="aboutNav">About Us</div>
            <div class="user">
                <a class="login" href="/login">
                    <?php if (isset($user)) : ?>
                        <img src="http://localhost:3000/<?php echo $user["profilePic"] ?>" alt="" />
                        <div><?php echo $user["username"] ?></div>
                    <?php else : ?>
                        <img src="./assets/images/default.png" alt="" />
                        <div>Login</div>
                    <?php endif; ?>

                </a>
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