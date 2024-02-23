<?php
require_once("src/database/connect.php");
if ($_SERVER["REQUEST_METHOD"] == "post") {
    extract($_POST);
} else {
    header("location: 404");
}
