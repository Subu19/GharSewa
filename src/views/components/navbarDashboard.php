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
            <img class="logo" id="logo" src="/assets/svgs/logoWide.svg" alt="" />
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
                <?php if (isset($user)) : ?>
                    <div class="userContainner">
                        <i class="material-icons notification">notifications</i>
                        <img onclick="document.getElementById('profileMenu').classList.toggle('hideProfileMenu')" src="http://localhost:3000/<?php echo $user['profilePic'] ?>" id="navProfilePic" class="navProfilePic"></img>
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
                            if (!element.classList.contains("hideProfileMenu") && !event.target.matches("#navProfilePic")) {
                                element.classList.add("hideProfileMenu");
                            }
                        })
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