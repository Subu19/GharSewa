<?php
require_once("src/database/connect.php");
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    extract($_POST);

    if ($_FILES['profilePic']['error'] == UPLOAD_ERR_OK) {
        // Profile picture was uploaded successfully
        $profilePicExists = true;
    } else {
        // No profile picture was uploaded or an error occurred
        $profilePicExists = false;
    }


    if ($profilePicExists) {

        $profilePath = uploadProfile();

        //hash password!
        $hashpassword = password_hash($password, PASSWORD_DEFAULT);

        //prepare sql statement
        $sql = "INSERT INTO user (first_name, last_name, username, email, phone, address, password, profilePic) 
                    VALUES (:first_name, :last_name, :username, :email, :phone, :address, :password, :profilePic)";
        $statement = $pdo->prepare($sql);
        $statement->bindParam(':first_name', $firstname);
        $statement->bindParam(':last_name', $lastname);
        $statement->bindParam(':username', $username);
        $statement->bindParam(':email', $email);
        $statement->bindParam(':phone', $phone);
        $statement->bindParam(':address', $address1);
        $statement->bindParam(':password', $hashpassword);
        $statement->bindParam(':profilePic', $profilePath);

        if ($statement->execute()) {
            header("location: /login");
            exit();
        } else {
            echo "Something went wrong!";
            exit();
        }
    } else {

        //hash password!
        $hashpassword = password_hash($password, PASSWORD_DEFAULT);

        //prepare sql statement
        $sql = "INSERT INTO user (first_name, last_name, username, email, phone, address, password) 
                    VALUES (:first_name, :last_name, :username, :email, :phone, :address, :password)";
        $statement = $pdo->prepare($sql);
        $statement->bindParam(':first_name', $firstname);
        $statement->bindParam(':last_name', $lastname);
        $statement->bindParam(':username', $username);
        $statement->bindParam(':email', $email);
        $statement->bindParam(':phone', $phone);
        $statement->bindParam(':address', $address1);
        $statement->bindParam(':password', $hashpassword);

        if ($statement->execute()) {
            header("location: /login");
            exit();
        } else {
            echo "Something went wrong!";
            exit();
        }
    }
} else {
    header("location: /404");
}


function uploadProfile()
{
    $target_dir = "uploads/";
    $target_file = $target_dir . uniqid();
    // $imageFileType = strtolower(pathinfo($_FILES["profilePic"]["name"], PATHINFO_EXTENSION));
    if (move_uploaded_file($_FILES["profilePic"]["tmp_name"], $target_file)) {
        return $target_file;
    } else {
        echo "Error while Uploading Profile Picture!";
    }
}
