<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
header('Content-Type: application/json');

require_once __DIR__ . '/../config/php/database.php';
require_once __DIR__ . '/../app/controllers/PostController.php';

$data = json_decode(file_get_contents("php://input"), true);

$userId = $data['userId'] ?? null;
$title = $data['title'] ?? '';
$image = $data['image'] ?? null;

if (!$userId || !$image) {
    http_response_code(400);
    echo json_encode(["success" => false, "error" => "Faltan datos"]);
    exit;
}

$imageData = explode(',', $image);
if (count($imageData) !== 2) {
    http_response_code(400);
    echo json_encode(["success" => false, "error" => "Formato de imagen inválido"]);
    exit;
}

$base64String = str_replace(' ', '+', $imageData[1]);
$decodedImage = base64_decode($base64String);
if ($decodedImage === false) {
    http_response_code(400);
    echo json_encode(["success" => false, "error" => "Base64 inválido"]);
    exit;
}
file_put_contents(__DIR__ . '/uploads/test.png', $decodedImage);
echo strlen($decodedImage);

$postController = new PostController();
$postId = $postController->createPost($userId, $title, $decodedImage);

if (!$postId) {
    http_response_code(500);
    echo json_encode(["success" => false, "error" => "No se pudo crear el post"]);
    exit;
}

echo json_encode([
    "success" => true,
    "postId" => $postId,
    "url" => "get_image.php?id=" . $postId
]);
