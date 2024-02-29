<?php


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
    "/admin/dashboard" => "src/views/dashboard/dashboard.php",
    "/postapprove" => "src/controllers/post/approve.php",
    "/postreject" => "src/controllers/post/reject.php",
    "/dashboard" => "src/views/user/dashboard/dashboard.php",
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
