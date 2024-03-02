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
    $sql = "SELECT username,profilePic FROM user where user_id = :userid";

    $statement = $pdo->prepare($sql);
    $statement->bindParam(":userid", $userid);
    $statement->execute();

    $user = $statement->fetch(PDO::FETCH_ASSOC);
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registe New Account</title>
    <link rel="stylesheet" href="src/css/main.css">
    <link rel="stylesheet" href="src/css/register/main.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

</head>

<body>
    <div class="wrapper">
        <div class="nav">
            <img src="assets/svgs/logoWide.svg" class="logo" alt="">
        </div>
        <form enctype="multipart/form-data" action="/postapply" method="post" onsubmit="return validateForm()">
            <div class="formTitle break">Work with us</div>
            <label class="takespace label">Please select your profession:</label>
            <select name="serviceType" class="select" id="serviceType">
                <option value="Plumber">Plumber</option>
                <option value="Electrician">Electrician</option>
                <option value="Cleaner">Cleaner</option>
                <option value="Babysitter">Babysitter</option>
                <option value="Cook">Cook</option>
                <option value="Painter">Painter</option>
                <option value="Technician">Technician</option>
                <option value="Health Check Up">Health Check Up</option>
            </select>
            <hr class="takespace">

            <label for="aboutme" class="label takespace">Introduce yourself (This section goes into your profile):</label>
            <textarea name="aboutme" class="break aboutme" id="aboutme" cols="30" rows="10"></textarea>
            <hr class="takespace">

            <label for="resume" class="label takespace">Upload your Resume(CV)</label>
            <input type="file" name="resume">
            <hr class="takespace">
            <label for="certificates" class="label takespace">Upload your achievements</label>
            <input type="file" name="certificates[]" accept="image/*" multiple>
            <hr class="takespace">
            <label for="citizenship_front" class="label takespace">Upload Citizenship</label>

            <div class="hcontainner">
                <div class="front flex">
                    <div class="subtitle">Front Image</div>
                    <input type="file" name="citizenship_front" onchange="previewCitizenship(this, 'frontPreview')"> <br>
                    <img id="frontPreview" src="" alt="" style="max-width: 200px; max-height: 200px; margin-top: 10px;" class="preview">
                </div>
                <div class="back flex">
                    <div class="subtitle">Back Image</div>
                    <input type="file" name="citizenship_back" onchange="previewCitizenship(this, 'backPreview')"> <br>
                    <img id="backPreview" src="" alt="" style="max-width: 200px; max-height: 200px; margin-top: 10px;" class="preview">
                </div>
            </div>

            <hr class="takespace">



            <button class="btn-primary takespace" type="submit">Apply</button>
        </form>
    </div>

    <script>
        function previewCitizenship(input, previewId) {
            var file = input.files[0];
            var reader = new FileReader();
            reader.onload = function(e) {
                var preview = document.getElementById(previewId);
                preview.src = e.target.result;
            };
            reader.readAsDataURL(file);
        }


        function validateForm() {
            var serviceType = document.getElementById("serviceType").value;
            var resume = document.querySelector('input[name="resume"]').files[0];
            var certificates = document.querySelector('input[name="certificates"]').files;
            var citizenshipFront = document.querySelector('input[name="citizenship_front"]').files[0];
            var citizenshipBack = document.querySelector('input[name="citizenship_back"]').files[0];
            // Validate service type
            if (serviceType.trim() === "") {
                alert("Please select your profession.");
                return false;
            }

            // Validate resume file type
            if (resume) {
                var allowedExtensions = /(\.pdf)$/i;
                if (!allowedExtensions.exec(resume.name)) {
                    alert('Invalid file type. Please upload a PDF file for your resume.');
                    return false;
                }
            } else {
                alert("Please upload your resume.");
                return false;
            }

            if (!citizenshipFront || !citizenshipBack) {
                alert("Please upload both front and back sides of your citizenship.");
                return false;
            }
            return true;
        }
    </script>
</body>

</html>