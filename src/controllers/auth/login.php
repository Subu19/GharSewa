<?php
require_once("src/database/connect.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if username and password were provided
    if (isset($_POST['username']) && isset($_POST['password'])) {
        extract($_POST);

        // Prepare SQL statement to fetch user data based on username
        $sql = "SELECT * FROM user WHERE username = :username";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':username', $username);

        // Execute the query
        $stmt->execute();

        // Fetch user data
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Check if user exists and password is correct
        if ($user && password_verify($password, $user['password'])) {
            // Login successful
            // store user information in session for further authentication
            session_start();
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['isAdmin'] = $user['isAdmin'];
            // Redirect user to dashboard or home page
            header("location: /");
            exit();
        } else {
            // Invalid username or password
            $error = "Invalid username or password.";
            header("location: /404");
            exit();
        }
    } else {
        // Username or password not provided
        $error = "Please enter both username and password.";
        header("location: /404");
        exit();
    }
} else {
    // If the request method is not POST, redirect user to a 404 page
    header("location: /404");
    exit();
}
