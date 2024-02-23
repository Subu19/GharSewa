<?php

$host = "localhost";
$username = "root";
$password = "";
$database = "gharsewa";

$mysql = mysqli_connect($host, $username, $password, $database);

if (!$mysql) {
    die('Could not connect to db server');
}
