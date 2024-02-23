<?php


$routes = [
    "/" => "src/views/index.php",
    "/login" => "src/views/login.php",
    "/register" => "src/views/register.php",
    "/postregister" => "src/controllers/auth/register.php",
    "/postlogin" => "src/controllers/auth/login.php",
    "/404" => "src/views/404.php"
];

$uri = parse_url($_SERVER["REQUEST_URI"])["path"];

if (array_key_exists($uri, $routes)) {
    require $routes[$uri];
} else {
   // include 'views/404.php';
    http_response_code(404);
}
