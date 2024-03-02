<?php

if (isset($_GET['id'])) {
    require_once("src/database/connect.php");
    $sql = "select qualifications,profilePic,user.user_id,service_type,description,status, work_time_end,work_time_start,identity_verify,document_verify,background_check,cover_image,first_name,last_name,username,sunday,monday,tuesday,wednesday,thursday,friday,saturday from worker inner join user on user.user_id = worker.user_id inner join working_days on worker.worker_id = working_days.worker_id where worker.worker_id = :wid;";
    $stmnt = $pdo->prepare($sql);
    $workerid = $_GET['id'];
    $stmnt->bindParam(":wid", $workerid);
    $stmnt->execute();

    $worker = $stmnt->fetch(PDO::FETCH_ASSOC);
    if (!isset($worker['service_type'])) {
        echo "Something went wrong! Please Try again!";
        exit();
    } else {
        $sql2 = "SELECT * FROM documents where user_id = :uid and type = 'CERTIFICATE';";
        $documents = $pdo->prepare($sql2);
        $user_id = $worker['user_id'];
        $documents->bindParam(":uid", $user_id);
        $documents->execute();
        $images = $documents->fetchAll(PDO::FETCH_ASSOC);
    }
} else {
    header("Location:/services");
    exit();
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="/src/css/main.css">
    <link rel="stylesheet" href="/src/css/navbar.css">
    <link rel="stylesheet" href="/src/css/worker/main.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

</head>

<body>
    <?php include "src/views/components/navbar.php" ?>
    <div class="wrapper content">
        <img src="http://localhost:3000/<?php echo $worker['cover_image'] ?>" alt="" class="coverImage">
        <div class="workerContainner">
            <div class="worker">
                <img src="http://localhost:3000/<?php echo $worker['profilePic'] ?>" alt="" class="workerProfile">
                <div class="workerInfo">
                    <div class="workerName">
                        <?php echo $worker['first_name'] ?> <?php echo $worker['last_name'] ?>
                    </div>
                    <div class="workerUsername">
                        @<?php echo $worker['username'] ?>
                    </div>
                    <div class="verifedTag">A Verified <strong class="highlight"><?php echo $worker['service_type'] ?></strong> <img class="verifyedIcon" src="/assets/svgs/verified.svg" alt=""></div>
                </div>
            </div>
            <!-- //put likes and all for future  -->
        </div>
        <div class="mainContent">
            <div class="left">
                <div class="detailContainner">
                    <div class="title">
                        Profile
                        <hr class="underline">
                    </div>
                    <div class="detail">
                        <?php echo $worker['description'] ?>
                    </div>
                </div>

                <div class="detailContainner">
                    <div class="title">
                        Get to Know Me
                        <hr class="underline">
                    </div>
                    <div class="detail">
                        <ul>
                            <?php
                            if ($worker['qualifications']) {
                                $data = json_decode($worker['qualifications']);
                                if ($data !== null) {
                                    foreach ($data as $string) {
                                        // Echo HTML for each string
                                        echo "<li>" . $string . "</li>";
                                    }
                                }
                            }

                            ?>
                        </ul>

                    </div>
                </div>

                <div class="detailContainner">
                    <div class="title">
                        My Achievements
                        <hr class="underline">
                    </div>
                    <div class="slideContainner">

                        <div class="slider">
                            <div class="controller">
                                <?php

                                foreach ($images as $index => $image) {
                                    echo '<div class="control" onclick="showSlide(' . $index . ')"></div>';
                                }
                                ?>
                            </div>
                            <?php
                            foreach ($images as $image) {
                                echo '<img src="http://localhost:3000/' . $image["url"] . '" alt="" class="slide">';
                            }
                            ?>
                        </div>

                    </div>
                </div>
            </div>
            <div class="right">
                <div class="detailContainner">
                    <div class="title2 blue">
                        Status
                    </div>
                    <div class="detail">
                        <?php echo $worker['status'] ?>
                    </div>
                </div>

                <div class="detailContainner">
                    <div class="title2 blue">
                        Location
                    </div>
                    <div class="detail">
                        <div class="locationDetail">
                            <img src="assets/svgs/greenLocation.svg" alt="" class="smallicon">
                            2.5km away
                        </div>

                    </div>
                </div>

                <div class="detailContainner">
                    <div class="title2 blue">
                        Working Time
                    </div>
                    <div class="detail">
                        <?php echo $worker['work_time_start'] ?>
                        <strong>to</strong>
                        <?php echo $worker['work_time_end'] ?>
                    </div>
                </div>

                <div class="detailContainner">
                    <div class="title2 blue">
                        <strong class="green">GharSewa</strong> Checks
                    </div>
                    <div class="detail">
                        <div class="check">
                            <?php echo $worker['identity_verify'] ? "✔️" : "❌" ?> Identify Verify
                        </div>
                        <div class="check">
                            <?php echo $worker['document_verify'] ? "✔️" : "❌" ?> Document Verify
                        </div>
                        <div class="check">
                            <?php echo $worker['background_check'] ? "✔️" : "❌" ?> Background Check
                        </div>
                    </div>
                </div>
                <div class="buttons">
                    <button class=" btn ">
                        Message Me
                    </button>
                    <hr>
                    <button class="btn btn-primary">
                        Hire Me
                    </button>
                </div>

            </div>
        </div>
    </div>
    <script src="/src/js/main.js"></script>
    <script src="/src/js/worker/worker.js"></script>
</body>

</html>