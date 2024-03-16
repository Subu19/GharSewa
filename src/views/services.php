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
    $sql = "SELECT w.worker_id, u.first_name, u.last_name, w.service_type, w.cover_image, u.map_lon, u.map_lat, COALESCE(r.total_reviews, 0) AS total_reviews, COALESCE(r.average_rating, 0) AS average_rating FROM worker w INNER JOIN user u ON u.user_id = w.user_id LEFT JOIN (SELECT worker_id, COUNT(*) AS total_reviews, AVG(rating) AS average_rating FROM reviews GROUP BY worker_id) r ON r.worker_id = w.worker_id WHERE w.approved = 1;";
    $stmnt = $pdo->prepare($sql);
    $stmnt->execute();

    ?>
    <div class="upperspace"></div>
    <div class="wrapper">
        <div class="hcontainner">
            <div class="search">
                <input onkeyup="updateFilter(this,1)" type="text" placeholder="Plumber near me" class="textinput radius-7">
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
                        <div class="dropitem"><input type="checkbox" value="Plumber" class="checkbox ProFilter" onchange="updateFilter(this)">Plumber</div>
                        <div class="dropitem"><input type="checkbox" value="Electrician" class="checkbox ProFilter" onchange="updateFilter(this)">Electrician</div>
                        <div class="dropitem"><input type="checkbox" value="Painter" class="checkbox ProFilter" onchange="updateFilter(this)">Painter</div>
                        <div class="dropitem"><input type="checkbox" value="Babysitter" class="checkbox ProFilter" onchange="updateFilter(this)">Babysitter</div>
                        <div class="dropitem"><input type="checkbox" value="Cook" class="checkbox ProFilter" onchange="updateFilter(this)">Cook</div>
                        <div class="dropitem"><input type="checkbox" value="Cleaner" class="checkbox ProFilter" onchange="updateFilter(this)">Cleaner</div>
                        <div class="dropitem"><input type="checkbox" value="Technician" class="checkbox ProFilter" onchange="updateFilter(this)">Technician</div>
                        <div class="dropitem"><input type="checkbox" value="Nurse" class="checkbox ProFilter" onchange="updateFilter(this)">Nurse</div>
                    </div>
                </div>

                <div class="dropdown">
                    <div class="d-title" onclick="toggleDropDown(this)">
                        <div class="title">Sort By</div>
                        <i class="material-icons toggleDropdownIcon">arrow_drop_down</i>
                    </div>
                    <div class="drops">
                        <div class="dropitem"><input class="checkbox" type="radio" name="sort" onclick="sortByBestRating()">Best</div>
                        <div class="dropitem"><input class="checkbox" type="radio" name="sort" onclick="sortByDistance()">Nearest</div>
                    </div>
                </div>
            </div>
            <div class="content">
                <div class="title">Workers from your search</div>
                <div class="hcontainner cards" id="workers">
                    <?php while ($row = $stmnt->fetch(PDO::FETCH_ASSOC)) : ?>
                        <a class="card" href="/worker?id=<?php echo $row['worker_id'] ?>">
                            <img src="<?php echo $row['cover_image'] ?>" alt="" class="card-img">
                            <div class="card-title"><?php echo $row['first_name'] ?> <?php echo $row['last_name'] ?></div>
                            <div class="card-details">
                                <div class="card-tag"><?php echo $row['service_type'] ?></div>
                                <div class="card-rating">
                                    <div class="rating2">
                                        <input value="5" type="radio" <?php echo $row['average_rating'] >= 5 ? "checked" : "" ?>>
                                        <label></label>
                                        <input value="4" type="radio" <?php echo $row['average_rating'] >= 4 ? "checked" : "" ?>>
                                        <label></label>
                                        <input value="3" type="radio" <?php echo $row['average_rating'] >= 3 ? "checked" : "" ?>>
                                        <label></label>
                                        <input value="2" type="radio" <?php echo $row['average_rating'] >= 2 ? "checked" : "" ?>>
                                        <label></label>
                                        <input value="1" type="radio" <?php echo $row['average_rating'] >= 1 ? "checked" : "" ?>>
                                        <label></label>
                                    </div>
                                    (<?php echo $row['total_reviews'] ?>)
                                </div>
                            </div>
                            <div class="card-footer">
                                <img src="assets/svgs/greenLocation.svg" alt="" class="card-smallicon">
                                <?php
                                echo isset($user['map_lon'], $user['map_lat'], $row['map_lon'], $row['map_lat'])
                                    ? number_format(calculateDistance($user['map_lat'], $user['map_lon'], $row['map_lat'], $row['map_lon']), 2) . " km"
                                    : "unknown";
                                ?>
                            </div>
                        </a>
                    <?php endwhile; ?>


                </div>
            </div>
        </div>
    </div>
    <script src="/src/js/main.js"></script>

    <script src="src/js/navbar.js"></script>
    <script src="src/js/services/main.js"></script>
</body>

</html>