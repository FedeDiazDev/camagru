<?php
$request = $_SERVER['REQUEST_URI'];

if (strpos($request, '?') !== false) {
    $request = substr($request, 0, strpos($request, '?'));
}

$request = rtrim($request, '/');
error_log("Request URI: $request");
switch ($request) {
    case '':
    case '/':
        include_once __DIR__ . '/../app/views/pages/home.php';
        exit;
        break;

    case '/camera':
        include_once __DIR__ . '/../app/views/camera.php';
        exit;
        break;

    case '/gallery':
        include_once __DIR__ . '/../app/views/gallery.php';
        exit;
        break;

    case '/login':
        include_once __DIR__ . '/../app/views/login.php';
        exit;
        break;
    case '/register':
        include_once __DIR__ . '/../app/views/register.php';
        exit;
        break;
    default:
        // Página no encontrada
        http_response_code(404);
        include_once __DIR__ . '/./404.html';
        exit;
        break;
}
?>

