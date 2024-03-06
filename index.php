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

////routes

$routes = [
    "/" => "src/views/index.php",
    "/login" => "src/views/login.php",
    "/register" => "src/views/register.php",
    "/postregister" => "src/controllers/auth/register.php",
    "/postlogin" => "src/controllers/auth/login.php",
    "/404" => "src/views/404.php",
    "/services" => "src/views/services.php",
    "/apply" => "src/views/application.php",
    "/postapply" => "src/controllers/post/application.php",
    "/admin/dashboard/pending" => "src/views/dashboard/pending.php",
    "/admin/dashboard/workers" => "src/views/dashboard/worker.php",
    "/admin/dashboard" => "src/views/dashboard/dashboard.php",
    "/postapprove" => "src/controllers/post/approve.php",
    "/postreject" => "src/controllers/post/reject.php",
    "/posthire" => "src/controllers/post/posthire.php",
    "/dashboard" => "src/views/user/dashboard/dashboard.php",
    "/dashboard/workprofile" => "src/views/user/dashboard/proProfile.php",
    "/dashboard/profile" => "src/views/user/dashboard/myprofile.php",
    "/dashboard/requests" => "src/views/user/dashboard/requests.php",
    "/dashboard/active" => "src/views/user/dashboard/active.php",

    "/update-pro-profile" => "src/controllers/post/updateProProfile.php",
    "/logout" => "src/views/logout.php",
    "/worker" => "src/views/worker.php",
    "/postReview" => "src/controllers/post/postReview.php",
    "/update-profile" => "src/controllers/post/updateProfile.php",
    "/postRequestAccept" => "src/controllers/post/postRequestAccept.php",
    "/postRequestReject" => "src/controllers/post/postRequestReject.php",
    "/postWorkComplete" => "src/controllers/post/postWorkComplete.php",
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
if (array_key_exists($uri, $routes)) {
    require $routes[$uri];
}
