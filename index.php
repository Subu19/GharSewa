<?php
//global functions
function calculateDistance($lat1, $lon1, $lat2, $lon2)
{
    // Convert latitude and longitude from degrees to radians
    $lat1 = deg2rad($lat1);
    $lon1 = deg2rad($lon1);
    $lat2 = deg2rad($lat2);
    $lon2 = deg2rad($lon2);

    $dlat = $lat2 - $lat1;
    $dlon = $lon2 - $lon1;

    $a = sin($dlat / 2) * sin($dlat / 2) + cos($lat1) * cos($lat2) * sin($dlon / 2) * sin($dlon / 2);
    $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

    // Earth radius in kilometers
    $R = 6371;

    // Calculate the distance
    $distance = $R * $c;

    return $distance;
}
//send notification 
function sendNotification($con, $uid, $title, $message, $link)
{
    $time = round(microtime(true) * 1000);
    $sql = "INSERT INTO notification(user_id,title,message,link,time) value(:uid,:title,:message,:link,:time);";
    $notifyStat = $con->prepare($sql);
    $notifyStat->bindParam(":uid", $uid);
    $notifyStat->bindParam(":title", $title);
    $notifyStat->bindParam(":message", $message);
    $notifyStat->bindParam(":link", $link);
    $notifyStat->bindParam(":time", $time);

    $notifyStat->execute();
}

function getUserFromWorker($con, $workerid)
{
    $sql = "SELECT user_id from worker where worker_id = :wid;";
    $workstat = $con->prepare($sql);
    $workstat->bindParam(":wid", $workerid);
    $workstat->execute();
    $worker = $workstat->fetch(PDO::FETCH_ASSOC);
    return $worker['user_id'];
}
function getUserFromOrder($con, $orderid)
{
    $sql = "SELECT user_id from orders where order_id = :oid;";
    $orderstat = $con->prepare($sql);
    $orderstat->bindParam(":oid", $orderid);
    $orderstat->execute();
    $order = $orderstat->fetch(PDO::FETCH_ASSOC);
    return $order['user_id'];
}

function formatTimestamp($timestamp)
{
    $now = time();
    $date = strtotime($timestamp);

    $diffSeconds = $now - $date;
    $diffDays = floor($diffSeconds / (60 * 60 * 24));

    if ($diffDays === 0) {

        return 'Today at ' . date('h:i A', $date);
    } elseif ($diffDays === 1) {

        return 'Yesterday at ' . date('h:i A', $date);
    } else {

        return date('Y-m-d h:i A', $date);
    }
}



// Define routes for GET requests
$getRoutes = [
    "/" => "src/views/index.php",
    "/login" => "src/views/login.php",
    "/register" => "src/views/register.php",
    "/404" => "src/views/404.php",
    "/services" => "src/views/services.php",
    "/apply" => "src/views/application.php",
    "/admin/dashboard/pending" => "src/views/dashboard/pending.php",
    "/admin/dashboard/workers" => "src/views/dashboard/worker.php",
    "/admin/dashboard" => "src/views/dashboard/dashboard.php",
    "/dashboard" => "src/views/user/dashboard/dashboard.php",
    "/dashboard/workprofile" => "src/views/user/dashboard/proProfile.php",
    "/dashboard/profile" => "src/views/user/dashboard/myprofile.php",
    "/dashboard/requests" => "src/views/user/dashboard/requests.php",
    "/dashboard/notifications" => "src/views/user/dashboard/notifications.php",
    "/dashboard/messages" => "src/views/user/dashboard/messages.php",
    "/dashboard/active" => "src/views/user/dashboard/active.php",
    "/worker" => "src/views/worker.php",
    "/logout" => "src/views/logout.php",
    "/redirect-notification" => "src/controllers/markasread.php",

];

// Define routes for POST requests
$postRoutes = [
    "/postregister" => "src/controllers/auth/register.php",
    "/postlogin" => "src/controllers/auth/login.php",
    "/postapply" => "src/controllers/post/application.php",
    "/postapprove" => "src/controllers/post/approve.php",
    "/postreject" => "src/controllers/post/reject.php",
    "/posthire" => "src/controllers/post/posthire.php",
    "/update-pro-profile" => "src/controllers/post/updateProProfile.php",
    "/postReview" => "src/controllers/post/postReview.php",
    "/update-profile" => "src/controllers/post/updateProfile.php",
    "/postRequestAccept" => "src/controllers/post/postRequestAccept.php",
    "/postRequestReject" => "src/controllers/post/postRequestReject.php",
    "/postWorkComplete" => "src/controllers/post/postWorkComplete.php",
    "/api/postMessage" => "src/api/postMessage.php",
    "/api/getMessage" => "src/api/getMessage.php",
    "/api/getworkerlist" => "src/api/getWorkerList.php",

];

$uri = parse_url($_SERVER["REQUEST_URI"])["path"];

if (strpos($uri, "/uploads/") === 0) {
    // Serve the file directly
    $filePath = __DIR__ . $uri;
    if (file_exists($filePath) && is_file($filePath)) {
        // Set appropriate headers
        header('Content-Type: ' . mime_content_type($filePath));
        header('Content-Length: ' . filesize($filePath));
        // Output the file content
        readfile($filePath);
        exit(); // Stop further execution
    } else {
        // File not found, return 404
        http_response_code(404);
        echo "File not found";
        exit(); // Stop further execution
    }
}

// Route the request based on method
if ($_SERVER['REQUEST_METHOD'] === 'GET' && array_key_exists($uri, $getRoutes)) {
    require $getRoutes[$uri];
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && array_key_exists($uri, $postRoutes)) {
    require $postRoutes[$uri];
} else {
    // Route not found, return 404
    http_response_code(404);
    echo "Page not found";
}
