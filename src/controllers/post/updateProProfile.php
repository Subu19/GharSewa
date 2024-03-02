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

    //fetch user details
    $user = $statement->fetch(PDO::FETCH_ASSOC);
    if (!$user['isWorker']) {
        echo "You are not authorized to access this!";
        exit();
    }
    $sql1 = "SELECT worker_id,cover_image FROM worker where user_id = :userid";

    $statement1 = $pdo->prepare($sql1);
    $statement1->bindParam(":userid", $userid);
    $statement1->execute();

    //fetch worker details
    $worker = $statement1->fetch(PDO::FETCH_ASSOC);
}

if ($_SERVER['REQUEST_METHOD'] === "POST") {

    $coverImage = $worker['cover_image'];

    // Check if cover image is uploaded
    if (isset($_FILES['cover_image']) && $_FILES['cover_image']['error'] === UPLOAD_ERR_OK) {
        $upload = uploadCoverImage();
        if ($upload !== null) {
            $coverImage = $upload;
        } else {
            // Handle upload failure
            // You may want to display an error message or log the error
        }
    }

    // Retrieve other form data
    $status = $_POST['status'];
    $work_time_start = $_POST['work_time_start'];
    $work_time_end = $_POST['work_time_end'];
    $sunday = isset($_POST['sunday']) ? '1' : '0';
    $monday = isset($_POST['monday']) ? '1' : '0';
    $tuesday = isset($_POST['tuesday']) ? '1' : '0';
    $wednesday = isset($_POST['wednesday']) ? '1' : '0';
    $thursday = isset($_POST['thursday']) ? '1' : '0';
    $friday = isset($_POST['friday']) ? '1' : '0';
    $saturday = isset($_POST['saturday']) ? '1' : '0';
    $aboutme = $_POST['aboutme'];
    $qualifications = isset($_POST['skills']) ? $_POST['skills'] : "[]";
    $workerid = $worker['worker_id'];

    // Update worker details
    $sql2 = "UPDATE worker SET qualifications = :qualifications, cover_image = :cover_image, status = :status, work_time_start = :work_time_start, work_time_end = :work_time_end, description = :aboutme WHERE worker_id = :worker_id;";
    $statement2 = $pdo->prepare($sql2);
    $statement2->bindParam(":cover_image", $coverImage);
    $statement2->bindParam(":status", $status);
    $statement2->bindParam(":work_time_start", $work_time_start);
    $statement2->bindParam(":work_time_end", $work_time_end);
    $statement2->bindParam(":aboutme", $aboutme);
    $statement2->bindParam(":qualifications", $qualifications);
    $statement2->bindParam(":worker_id", $workerid);
    $statement2->execute();

    // Update working days
    $sql3 = "UPDATE working_days SET sunday = :sunday, monday = :monday, tuesday = :tuesday, wednesday = :wednesday, thursday = :thursday, friday = :friday, saturday = :saturday WHERE worker_id = :worker_id;";
    $statement3 = $pdo->prepare($sql3);
    $statement3->bindParam(":sunday", $sunday);
    $statement3->bindParam(":monday", $monday);
    $statement3->bindParam(":tuesday", $tuesday);
    $statement3->bindParam(":wednesday", $wednesday);
    $statement3->bindParam(":thursday", $thursday);
    $statement3->bindParam(":friday", $friday);
    $statement3->bindParam(":saturday", $saturday);
    $statement3->bindParam(":worker_id", $workerid);
    $statement3->execute();

    header("Location: /dashboard/workprofile");
    exit();
}

function uploadCoverImage()
{
    $target_dir = "uploads/coverimages/";
    $target_file = $target_dir . uniqid() . "CoverImage";
    if (move_uploaded_file($_FILES["cover_image"]["tmp_name"], $target_file)) {
        return $target_file;
    } else {
        return null;
    }
}
