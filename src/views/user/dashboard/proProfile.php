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
    <link rel="stylesheet" href="/src/css/dashboard/proProfile.css">

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
                        <a class="dashNav" href="/dashboard/requests">
                            <i class="material-icons">warning</i>
                            Requests
                        </a>
                        <a class="dashNav" href="/dashboard/active">
                            <i class="material-icons">label</i>
                            Active
                        </a>
                        <a class="dashNav selected" href="/dashboard/workprofile">
                            <i class="material-icons">account_circle</i>
                            My Pro Profile
                        </a>
                    <?php endif; ?>
                </div>
            </div>
            <div class="right">
                <div class="contentContainner">
                    <?php
                    $workersql = "SELECT * from worker INNER JOIN user on user.user_id = worker.user_id INNER JOIN working_days on worker.worker_id = working_days.worker_id where user.user_id = :id ;";
                    $stat = $pdo->prepare($workersql);
                    $stat->bindParam(":id", $userid);
                    $stat->execute();
                    $worker = $stat->fetch(PDO::FETCH_ASSOC);

                    $documentsql = "SELECT * from documents where user_id = :id;";
                    $stat1 = $pdo->prepare($documentsql);
                    $stat1->bindParam(":id", $userid);
                    $stat1->execute();

                    ?>

                    <div class="userdetails">


                        <div class="serviceType st-big">
                            <?php echo $worker['service_type'] ?>
                        </div>
                        <div class="break"></div>
                        <form onsubmit="return handleProfileSubmit()" action="/update-pro-profile" method="post" class="updateform" enctype="multipart/form-data">

                            <div class="detail">
                                <div class="title">Cover Image:</div>
                                <img id="coverImagePreview" src="http://localhost:3000/<?php echo $worker['cover_image'] ?>" alt="" class="coverImage">
                                <br>
                                <input name="cover_image" onchange="preview(this,'coverImagePreview')" id="coverImage" accept="image/*" type="file" hidden />
                                <button type="button" onclick="document.getElementById('coverImage').click()" class="btn"><i class="material-icons">add_to_photos</i>
                                    Change</button>
                            </div>

                            <div class="break"></div>

                            <div class="detail-wide">
                                <div class="title">Status: </div>
                                <select class="input" name="status" id="">
                                    <option value="Available" <?php if ($worker['status'] == 'Available') echo "selected" ?>>Available</option>
                                    <option value="Booked" <?php if ($worker['status'] == 'Booked') echo "selected" ?>>Booked</option>
                                    <option value="Unavailable" <?php if ($worker['status'] == 'Unavailable') echo "selected" ?>>Unavailable</option>
                                </select>
                            </div>

                            <div class="detail-wide">
                                <div class="title">Working Hours: (10AM to 4PM) </div>
                                <input name="work_time_start" type="text" class="input" value="<?php echo $worker["work_time_start"] ?>">&nbsp;&nbsp; to &nbsp;&nbsp; <input name="work_time_end" value="<?php echo $worker["work_time_end"] ?>" type="text" class="input">
                            </div>

                            <div class="detail">
                                <div class="title">Working Days:</div>
                                <div class="checkboxes">
                                    <div class="checkbox">
                                        <input type="checkbox" class="checkboxInput" name="sunday" <?php if ($worker['sunday']) echo "checked" ?>> Sunday
                                    </div>
                                    <div class="checkbox">
                                        <input type="checkbox" class="checkboxInput" name="monday" <?php if ($worker['monday']) echo "checked" ?>> Monday
                                    </div>
                                    <div class="checkbox">
                                        <input type="checkbox" class="checkboxInput" name="tuesday" <?php if ($worker['tuesday']) echo "checked" ?>> Tuesday
                                    </div>
                                    <div class="break"></div>

                                    <div class="checkbox">
                                        <input type="checkbox" class="checkboxInput" name="wednesday" <?php if ($worker['wednesday']) echo "checked" ?>> Wednesday
                                    </div>
                                    <div class="checkbox">
                                        <input type="checkbox" class="checkboxInput" name="thursday" <?php if ($worker['thursday']) echo "checked" ?>> Thursday
                                    </div>
                                    <div class="checkbox">
                                        <input type="checkbox" class="checkboxInput" name="friday" <?php if ($worker['friday']) echo "checked" ?>> Friday
                                    </div>
                                    <div class="break"></div>

                                    <div class="checkbox">
                                        <input type="checkbox" class="checkboxInput" name="saturday" <?php if ($worker['saturday']) echo "checked" ?>> Saturday
                                    </div>
                                </div>
                            </div>

                            <div class="detail-wide">
                                <div class="title">About Me:</div>
                                <textarea name="aboutme" class="textarea"><?php echo $worker["description"] ?></textarea>
                            </div>

                            <div class="detail-wide">
                                <div class="title">My Skills/Qualifications:</div>
                                <input type="text" name="skills" hidden id="skills">
                                <div class="skillContainner" id="skillContainner">
                                    <?php
                                    if ($worker['qualifications']) {
                                        $data = json_decode($worker['qualifications']);
                                        if ($data !== null) {
                                            foreach ($data as $string) {
                                                // Echo HTML for each string
                                                echo '<div class="skill">';
                                                echo '<input type="text" class="input qualification" value="' . $string . '">';
                                                echo '<button type="button" class="btn delete" onclick="deleteSkill(this)">❌</button>';
                                                echo '</div>';
                                            }
                                        }
                                    }

                                    ?>
                                </div>

                                <button type="button" class="btn" onclick="addMoreSkill()">Add More</button>
                            </div>
                            <div class="detail-wide">
                                <div class="title">Hourly Rate:</div>
                                Rs. <input step=".01" type="number" class="input" name="hourly_rate" value="<?php echo $worker["hourly_rate"] ?>">
                            </div>
                            <button class="btn-primary" type="submit">Update Profile</button>

                        </form>

                        <div class="break"></div>
                        <hr>
                        <?php while ($document = $stat1->fetch(PDO::FETCH_ASSOC)) : ?>

                            <?php if ($document['type'] === 'CITIZENSHIP_FRONT') : ?>
                                <div class="detail">
                                    <div class="title">Citizenship Front:</div>
                                    <img src="http://localhost:3000/<?php echo $document['url'] ?>" alt="" class="document">
                                </div>
                                <div class="break"></div>

                            <?php endif; ?>
                            <?php if ($document['type'] === 'CITIZENSHIP_BACK') : ?>
                                <div class="detail">
                                    <div class="title">Citizenship Back:</div>
                                    <img src="http://localhost:3000/<?php echo $document['url'] ?>" alt="" class="document">
                                </div>
                                <div class="break"></div>

                            <?php endif; ?>
                            <?php if ($document['type'] === 'RESUME') : ?>
                                <div class="detail">
                                    <a href="http://localhost:3000/<?php echo $document['url'] ?>"><button class="btn">View Resume</button></a>
                                </div>
                                <div class="break"></div>

                            <?php endif; ?>

                            <?php if ($document['type'] === 'CERTIFICATE') : ?>
                                <div class="detail">
                                    <div class="title">Certificate/Achivement:</div>
                                    <img src="http://localhost:3000/<?php echo $document['url'] ?>" alt="" class="document">
                                </div>
                                <div class="break"></div>

                            <?php endif; ?>

                        <?php endwhile; ?>
                        <div class="break"></div>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <script src="/src/js/main.js"></script>

    <script src="/src/js/dashboard/proProfile.js"></script>
</body>

</html>