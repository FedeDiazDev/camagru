<?php

require_once __DIR__ . '/../config/php/database.php';
require_once __DIR__ . '/../app/controllers/PostController.php';

$data = json_decode(file_get_contents("php://input"), true);

$userId = $data['userId'] ?? null;
$title  = $data['title']  ?? '';
$image  = $data['image']  ?? null;

if (!$userId || !$image) {
    http_response_code(400);
    echo json_encode(["error" => "Faltan datos"]);
    exit;
}

$imageData = explode(',', $image);
if (count($imageData) !== 2) {
    http_response_code(400);
    echo json_encode(["error" => "Formato de imagen inválido"]);
    exit;
}
$decodedImage = base64_decode($imageData[1]);

$uploadDir = __DIR__ . '/uploads/';
if (!is_dir($uploadDir)) {
    if (!mkdir($uploadDir, 0755, true)) {
        http_response_code(500);
        echo json_encode(["error" => "No se pudo crear la carpeta de uploads"]);
        exit;
    }
}

$fileName = uniqid('photo_', true) . '.png';
$filePath = $uploadDir . $fileName;

if (file_put_contents($filePath, $decodedImage) === false) {
    http_response_code(500);
    echo json_encode(["error" => "Error al guardar la imagen"]);
    exit;
}

$mediaUrl = '/uploads/' . $fileName;

$postController = new PostController();
$postId = $postController->createPost($userId, $title, $mediaUrl);

echo json_encode([
    "success" => true,
    "postId"  => $postId,
    "url"     => $mediaUrl
]);
