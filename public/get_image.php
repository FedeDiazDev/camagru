<?php
require_once __DIR__ . '/../config/php/database.php';

if (!isset($_GET['id'])) {
    http_response_code(400);
    exit('Missing ID');
}

$id = intval($_GET['id']);
if ($id <= 0) {
    http_response_code(400);
    exit('Invalid ID');
}

try {
    $db = new Database();
    $pdo = $db->connect();

    $stmt = $pdo->prepare("SELECT mediaUrl FROM post WHERE id = :id LIMIT 1");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();

    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$row || empty($row['mediaUrl'])) {
        http_response_code(404);
        exit('Image not found');
    }

    // mediaUrl guarda "/images/posts/xxxxx.png"
    $relative = $row['mediaUrl'];

    // Convertirlo a ruta física dentro del contenedor
    $path = $_SERVER['DOCUMENT_ROOT'] . $relative;

    if (!file_exists($path)) {
        http_response_code(404);
        exit('File not found');
    }

    $mime = mime_content_type($path);
    header("Content-Type: $mime");
    readfile($path);
    exit;

} catch (PDOException $e) {
    http_response_code(500);
    exit('Database error');
}
