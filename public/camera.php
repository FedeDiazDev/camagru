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
$baseImage = $data['baseImage'] ?? null;
$stickers = $data['stickers'] ?? [];
$filter = $data['filter'] ?? 'none';
$brightness = $data['brightness'] ?? 100;
$contrast = $data['contrast'] ?? 100;

if (!$userId) {
    http_response_code(400);
    echo json_encode(["success" => false, "error" => "Faltan datos"]);
    exit;
}

$postController = new PostController();
$postId = $postController->createPost($userId, $title, $baseImage, $stickers, $filter, $brightness, $contrast);

if (!is_numeric($postId)) {
    http_response_code(500);
    echo json_encode(["success" => false, "error" => $postId]);
    exit;
}

echo json_encode([
    "success" => true,
    "postId" => $postId,
    "url" => "get_image.php?id=" . $postId
]);
