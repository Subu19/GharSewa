<?php
// Database credentials
$host = "localhost"; // Change this to your database host
$dbname = "gharsewa"; // Change this to your database name
$username = "root"; // Change this to your database username
$password = "subu19"; // Change this to your database password

try {
    // Create a new PDO instance
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);

    // Set the PDO error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Set character set to utf8mb4 for proper unicode support
    $pdo->exec("set names utf8mb4");
} catch (PDOException $e) {
    // If connection fails, display error message
    die("Connection failed: " . $e->getMessage());
}
