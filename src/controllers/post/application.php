<?php
require_once "src/database/connect.php";


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

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate other form fields
    $serviceType = $_POST["serviceType"];
    $aboutMe = $_POST["aboutme"];


    // Process uploaded files
    $targetDir = "uploads/";
    $resumeName = basename($_FILES["resume"]["name"]);
    $resumeTargetPath = $targetDir . "resume/" . uniqid() . $resumeName;

    $citizenshipFrontName = basename($_FILES["citizenship_front"]["name"]);
    $citizenshipFrontTargetPath = $targetDir . "citizenship/" . uniqid() . $citizenshipFrontName;

    $citizenshipBackName = basename($_FILES["citizenship_back"]["name"]);
    $citizenshipBackTargetPath = $targetDir . "citizenship/" . uniqid() . $citizenshipBackName;

    // Process certificate uploads
    if (!empty($_FILES["certificates"]["name"]) && is_array($_FILES["certificates"]["name"])) {
        foreach ($_FILES["certificates"]["name"] as $key => $certificateName) {
            $certificateTmpName = $_FILES["certificates"]["tmp_name"][$key];
            $certificateFileName = basename($certificateName);
            $certificateTargetPath = $targetDir . "certificates/" . $certificateFileName;

            if (move_uploaded_file($certificateTmpName, $certificateTargetPath)) {
                // File uploaded successfully
                $sql = "INSERT INTO documents(type, url, user_id) VALUES(:type,:url,:user_id)";
                $statement = $pdo->prepare($sql);
                $statement->bindParam(":type", "CERTIFICATE");
                $statement->bindParam(":url", $certificateTargetPath);
                $statement->bindParam(":user_id", $userid);
                $statement->execute();
            } else {
                // Error occurred while uploading certificate file
                echo "Sorry, there was an error uploading one of your certificate files.";
            }
        }
    } elseif (!empty($_FILES["certificates"]["name"])) {
        $certificateTmpName = $_FILES["certificates"]["tmp_name"];
        $certificateFileName = basename($_FILES["certificates"]["name"]);
        $certificateTargetPath = $targetDir . "certificates/" . uniqid() . $certificateFileName;

        if (move_uploaded_file($certificateTmpName, $certificateTargetPath)) {
            // File uploaded successfully
            $sql = "INSERT INTO documents(type, url, user_id) VALUES(:type,:url,:user_id)";
            $statement = $pdo->prepare($sql);
            $type = "CERTIFICATE";
            $statement->bindParam(':type', $type);
            $statement->bindParam(':url', $certificateTargetPath);
            $statement->bindParam(":user_id", $userid);
            $statement->execute();
        } else {
            // Error occurred while uploading certificate file
            echo "Sorry, there was an error uploading one of your certificate files.";
        }
    }

    // Move files to the uploads directory
    if (
        move_uploaded_file($_FILES["resume"]["tmp_name"], $resumeTargetPath) &&
        move_uploaded_file($_FILES["citizenship_front"]["tmp_name"], $citizenshipFrontTargetPath) &&
        move_uploaded_file($_FILES["citizenship_back"]["tmp_name"], $citizenshipBackTargetPath)
    ) {
        $sql = "INSERT INTO documents(type, url, user_id) VALUES(:type1,:url1,:user_id1), (:type2,:url2,:user_id2), (:type3,:url3,:user_id3);";
        $statement = $pdo->prepare($sql);

        $type1 = "RESUME";
        $statement->bindParam(":type1", $type1);
        $statement->bindParam(":url1", $resumeTargetPath);
        $statement->bindParam(":user_id1", $userid);

        $type2 = "CITIZENSHIP_FRONT";
        $statement->bindParam(":type2", $type2);
        $statement->bindParam(":url2", $citizenshipFrontTargetPath);
        $statement->bindParam(":user_id2", $userid);

        $type3 = "CITIZENSHIP_BACK";
        $statement->bindParam(":type3", $type3);
        $statement->bindParam(":url3", $citizenshipBackTargetPath);
        $statement->bindParam(":user_id3", $userid);

        $statement->execute();

        //registe new worker
        $sql2 = "INSERT INTO worker(user_id,service_type,description,completed_jobs,identity_verify,document_verify,background_check)
         VALUES(:user_id,:service_type,:description,:completed_jobs,:identity_verify,:document_verify,:background_check);";
        $statement2 = $pdo->prepare($sql2);
        $completed_jobs = "0";
        $identity_verify = 0;
        $document_verify = 0;
        $background_check = 0;

        $statement2->bindParam(":user_id", $userid);
        $statement2->bindParam(":service_type", $serviceType);
        $statement2->bindParam(":description", $aboutMe);
        $statement2->bindParam(":completed_jobs", $completed_jobs);
        $statement2->bindParam(":identity_verify", $identity_verify);
        $statement2->bindParam(":document_verify", $document_verify);
        $statement2->bindParam(":background_check", $background_check);

        $statement2->execute();




        header("Location: /services");
    } else {
        // Error occurred while uploading filly

        echo "Sorry, there was an error uploading your files.";
    }
} else {
    // Redirect to the form page if accessed directly
    header("Location: /apply");
    exit();
}
