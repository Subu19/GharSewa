<?php
session_start();
require_once "src/database/connect.php";
$filterParams = $_POST;
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';
$whereClause = [];
$parameters = [];

foreach ($filterParams as $key => $value) {
    if ($value === '1') {
        $whereClause[] = "service_type = ?";
        $parameters[] = $key;
    }
}

// Prepare the SQL statement to search for workers
$sql = "SELECT w.worker_id, u.first_name, u.last_name, w.service_type, w.cover_image, u.map_lon, u.map_lat, COALESCE(r.total_reviews, 0) AS total_reviews, COALESCE(r.average_rating, 0) AS average_rating FROM worker w INNER JOIN user u ON u.user_id = w.user_id LEFT JOIN (SELECT worker_id, COUNT(*) AS total_reviews, AVG(rating) AS average_rating FROM reviews GROUP BY worker_id) r ON r.worker_id = w.worker_id";

// Check if there is a search term
if (!empty($searchTerm)) {
    $sql .= " WHERE u.first_name LIKE :search OR u.last_name LIKE :search OR w.service_type LIKE :search";
    $parameters['search'] = "%" . $searchTerm . "%";
}

// If there are additional filter parameters
if (!empty($whereClause)) {
    if (!empty($searchTerm)) {
        $sql .= " AND (" . implode(" OR ", $whereClause) . ")";
    } else {
        $sql .= " WHERE " . implode(" OR ", $whereClause);
    }
}

$stmt = $pdo->prepare($sql);
$stmt->execute($parameters);

$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (!isset($_SESSION['user_id']) || !isset($_SESSION['username'])) {
    foreach ($results as &$worker) {
        $worker['distance'] = "unknown";
    }
} else {
    $sql = "SELECT map_lat, map_lon from user where user_id = :uid";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(":uid", $_SESSION['user_id']);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    foreach ($results as &$worker) { // Using reference to update $results array
        if (isset($worker['map_lat']) && isset($worker['map_lon']) && isset($user['map_lat']) && isset($user['map_lon'])) {
            $distance = calculateDistance($user['map_lat'], $user['map_lon'], $worker['map_lat'], $worker['map_lon']);
            $worker['distance'] = number_format($distance, 2);
        } else {
            $worker['distance'] = "unknown";
        }
    }
}

header("Content-Type: application/json");
echo json_encode($results);
