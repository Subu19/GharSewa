<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="src/css/navbar.css">
    <link rel="stylesheet" href="src/css/main.css">
    <link rel="stylesheet" href="src/css/services/main.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

</head>

<body>
    <?php include "src/views/components/navbar.php" ?>
    <?php
    require_once("src/database/connect.php");
    $sql = "SELECT worker_id,first_name,last_name,service_type,cover_image from worker INNER JOIN user on user.user_id = worker.user_id where worker.approved =1;";
    $stmnt = $pdo->prepare($sql);
    $stmnt->execute();

    ?>
    <div class="upperspace"></div>
    <div class="wrapper">
        <div class="hcontainner">
            <div class="search">
                <input type="text" placeholder="Plumber near me" class="textinput radius-7">
                <span class="searchIcon"><i class="material-icons icon" style="font-size: 40px;">search</i>
                </span>
            </div>
            </input>
            <div class="hcontainner locationInfo alignCenter radius-7">
                <div>We find the nearest best HandyMan</div>
                <img src="assets/svgs/locationIcon.svg" alt="" class="locationIcon">
            </div>
        </div>
        <div class="hcontainner main">
            <div class="filter">
                <div class="title">Search Filter</div>
                <div class="dropdown">
                    <div class="d-title" onclick="toggleDropDown(this)">
                        <div class="title">Category</div>
                        <i class="material-icons toggleDropdownIcon">arrow_drop_down</i>
                    </div>
                    <div class="drops">
                        <div class="dropitem">Plumber</div>
                        <div class="dropitem">Electrician</div>
                        <div class="dropitem">Cleaner</div>
                        <div class="dropitem">hmmm</div>
                        <div class="dropitem">Plumber</div>
                        <div class="dropitem">Plumber</div>
                        <div class="dropitem">Plumber</div>

                    </div>
                </div>

                <div class="dropdown">
                    <div class="d-title" onclick="toggleDropDown(this)">
                        <div class="title">Sort By</div>
                        <i class="material-icons toggleDropdownIcon">arrow_drop_down</i>
                    </div>
                    <div class="drops">
                        <div class="dropitem">Nearest</div>
                        <div class="dropitem">Relavent</div>
                        <div class="dropitem">Best</div>
                        <div class="dropitem">New Agents</div>

                    </div>
                </div>
            </div>
            <div class="content">
                <div class="title">Plumber near you</div>
                <div class="hcontainner cards">
                    <?php while ($row = $stmnt->fetch(PDO::FETCH_ASSOC)) : ?>
                        <a class="card" href="/worker?id=<?php echo $row['worker_id'] ?>">
                            <img src="<?php echo $row['cover_image'] ?>" alt="" class="card-img">
                            <div class="card-title"><?php echo $row['first_name'] ?> <?php echo $row['last_name'] ?></div>
                            <div class="card-details">
                                <div class="card-tag"><?php echo $row['service_type'] ?></div>
                                <div class="card-ratings">⭐⭐⭐⭐⭐(10)</div>
                            </div>
                            <div class="card-footer">
                                <img src="assets/svgs/greenLocation.svg" alt="" class="card-smallicon">
                                2.5km away
                            </div>
                        </a>
                    <?php endwhile; ?>


                </div>
            </div>
        </div>
    </div>

    <script src="src/js/navbar.js"></script>
    <script src="src/js/services/main.js"></script>
</body>

</html>