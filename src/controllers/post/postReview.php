
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
}

if ($_SERVER['REQUEST_METHOD'] === "POST") {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $sql = "INSERT INTO reviews (user_id,worker_id,rating,comment) VALUES (:user_id,:worker_id,:rating,:comment);";

        $worker_id = $_POST['worker_id'];
        $rating = $_POST['rating'];
        $comment = $_POST['comment'];

        $statement = $pdo->prepare($sql);
        $statement->bindParam(":user_id", $userid);
        $statement->bindParam(":worker_id", $worker_id);
        $statement->bindParam(":rating", $rating);
        $statement->bindParam(":comment", $comment);


        if ($statement->execute()) {
            header("Location: /worker?id=" . $worker_id);
        } else {
            echo "Something went wrong!";
        }
    } else {
        header("Location: /services");
    }
}
