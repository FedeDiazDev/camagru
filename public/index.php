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

    case '/camera':
        include_once __DIR__ . '/../app/views/camera.php';
        exit;

    case '/gallery':
        include_once __DIR__ . '/../app/views/gallery.php';
        exit;
    case '/login':
        include_once __DIR__ . '/../app/views/login.php';
        exit;
    case '/register':
        include_once __DIR__ . '/../app/views/register.php';
        exit;
    case '/profile':
        include_once __DIR__ . '/../app/views/pages/profile.php';
        exit;
    case '/post':
        include_once __DIR__ . '/../app/views/pages/post.php';
        exit;
    case '/logout':
        include_once __DIR__ . '/../app/views/pages/logout.php';
        exit;
    case '/password':
        include_once __DIR__ . '/../app/views/pages/forgotPassword.php';
        exit;
    default:
        http_response_code(404);
        include_once __DIR__ . '/./404.html';
        exit;
}
