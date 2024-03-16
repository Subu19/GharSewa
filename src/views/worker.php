<?php

if (isset($_GET['id'])) {
    require_once("src/database/connect.php");
    $sql = "select hourly_rate,map_lon,map_lat,qualifications,profilePic,user.user_id,service_type,description,status, work_time_end,work_time_start,identity_verify,document_verify,background_check,cover_image,first_name,last_name,username,sunday,monday,tuesday,wednesday,thursday,friday,saturday from worker inner join user on user.user_id = worker.user_id inner join working_days on worker.worker_id = working_days.worker_id where worker.worker_id = :wid;";
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

    //get reviews!

    $rsql = "select first_name,last_name,username,rating,comment,profilePic from reviews inner join user on user.user_id = reviews.user_id where worker_id = :wid;";
    $reviews = $pdo->prepare($rsql);
    $reviews->bindParam(":wid", $workerid);
    $reviews->execute();

    $ravgsql = "SELECT worker_id, AVG(rating) AS average_rating FROM reviews WHERE worker_id = :wid;";
    $avgstmnt = $pdo->prepare($ravgsql);
    $avgstmnt->bindParam(":wid", $workerid);
    $avgstmnt->execute();
    $rating = $avgstmnt->fetch(PDO::FETCH_ASSOC);
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
    <link rel="stylesheet" href="/src/css/worker/hireme.css">

    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <?php include "src/views/components/mapApi.php" ?>
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

                <?php if (isset($worker['map_lon']) && isset($worker['map_lat'])) : ?>
                    <div class="detailContainner">
                        <div class="btn-none" onclick="initMap(<?php echo $worker['map_lat'] ?>,<?php echo $worker['map_lon'] ?>)">View Map Location</div>
                        <div id="map"></div>
                    </div>
                <?php endif; ?>
            </div>
            <div class="right">
                <div class="detailContainner">
                    <div class="title2 blue">
                        Hourly Rate:
                    </div>
                    <div class="detail">
                        <strong> Rs. <?php echo $worker['hourly_rate'] ?></strong>
                    </div>
                </div>
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
                            <?php
                            echo isset($user['map_lon'], $user['map_lat'], $worker['map_lon'], $worker['map_lat'])
                                ? number_format(calculateDistance($user['map_lat'], $user['map_lon'], $worker['map_lat'], $worker['map_lon']), 2) . " km"
                                : "unknown";
                            ?>
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
                            <?php echo !$worker['identity_verify'] ? "✔️" : "❌" ?> Identify Verify
                        </div>
                        <div class="check">
                            <?php echo !$worker['document_verify'] ? "✔️" : "❌" ?> Document Verify
                        </div>
                        <div class="check">
                            <?php echo !$worker['background_check'] ? "✔️" : "❌" ?> Background Check
                        </div>
                    </div>
                </div>
                <div class="buttons">

                    <a href="/dashboard/messages?id=<?php echo $worker['user_id'] ?>" class="btn">
                        Message Me
                    </a>
                    <hr>
                    <?php
                    //check order
                    $ordersql = "SELECT * from orders where user_id = :uid and status = 'Pending' and worker_id = :wid;";
                    $orderstmnt = $pdo->prepare($ordersql);
                    $orderstmnt->bindParam(":uid", $userid);
                    $orderstmnt->bindParam(":wid", $workerid);
                    $orderstmnt->execute();
                    if ($orderstmnt->fetch(PDO::FETCH_ASSOC)) : ?>
                        <button class="btn btn-transparent">
                            Pending....
                        </button>
                    <?php elseif ($orderstmnt->rowCount() == 0) : ?>
                        <button class="btn btn-primary" onclick="toggleHire()" <?php echo isset($user) ? "" : "disabled" ?>>
                            Hire Me
                        </button>
                    <?php endif; ?>
                </div>

            </div>
        </div>
        <?php include "src/views/components/hireme.php" ?>
        <hr class="seperator">

        <div class="reviews">
            <div class=" title ReviewTitle">Reviews (<div class="avg"><?php echo $rating['average_rating'] ? $rating['average_rating'] : "0" ?> / 5 </div>)
            </div>

            <?php while ($review = $reviews->fetch(PDO::FETCH_ASSOC)) : ?>
                <div class="reviewContainner">
                    <img src="http://localhost:3000/<?php echo $review['profilePic'] ?>" alt="" class="rImage">
                    <div class="review">
                        <div class="rUsername"><?php echo $review['first_name'] ?> <?php echo $review['last_name'] ?></div>
                        <div class="rStars">
                            <div class="rating2">
                                <input value="5" type="radio" <?php echo $review['rating'] == 5 ? "checked" : "" ?>>
                                <label></label>
                                <input value="4" type="radio" <?php echo $review['rating'] == '4' ? "checked" : "" ?>>
                                <label></label>
                                <input value="3" type="radio" <?php echo $review['rating'] == 3 ? "checked" : "" ?>>
                                <label></label>
                                <input value="2" type="radio" <?php echo $review['rating'] == 2 ? "checked" : "" ?>>
                                <label></label>
                                <input value="1" type="radio" <?php echo $review['rating'] == 1 ? "checked" : "" ?>>
                                <label></label>
                            </div>
                        </div>
                        <div class="rComment"><?php echo $review['comment'] ?></div>
                    </div>
                </div>
            <?php endwhile; ?>
            <form class="reviewsForm" action="/postReview" method="post">
                <input type="text" name="worker_id" value="<?php echo $workerid ?>" hidden>
                <textarea required name="comment" type="text" class="textarea" placeholder="Write what you think of this worker!"></textarea>
                <div class="rating">
                    <input value="5" name="rating" id="star5" type="radio">
                    <label for="star5"></label>
                    <input value="4" name="rating" id="star4" type="radio" checked>
                    <label for="star4"></label>
                    <input value="3" name="rating" id="star3" type="radio">
                    <label for="star3"></label>
                    <input value="2" name="rating" id="star2" type="radio">
                    <label for="star2"></label>
                    <input value="1" name="rating" id="star1" type="radio">
                    <label for="star1"></label>
                </div>

                <br>
                <button type="submit" class="btn btn-transparent" <?php echo isset($user) ? "" : "disabled" ?>>Send</button>
            </form>
        </div>

    </div>
    <script src="/src/js/main.js"></script>
    <script src="src/js/navbar.js"></script>
    <script src="/src/js/worker/worker.js"></script>
    <script src="/src/js/map.js"></script>
</body>

</html>