<?php
require_once __DIR__ . '/../config/php/database.php';
ini_set('display_errors', 0);
error_reporting(0);

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
    // Ajusta según tu clase Database. En tu proyecto usas new Database()->connect() en varios sitios.
    $db = new Database();
    $pdo = $db->connect();

    // Obtén la longitud también para Content-Length
    $stmt = $pdo->prepare("SELECT OCTET_LENGTH(mediaUrl) AS len, mediaUrl FROM post WHERE id = :id LIMIT 1");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$row) {
        http_response_code(404);
        exit('Not found');
    }

    $blob = $row['mediaUrl'];
    $len = (int)($row['len'] ?? strlen($blob));

    if ($len === 0) {
        http_response_code(404);
        exit('Empty image');
    }

    // Detectar MIME por las cabeceras mágicas
    $mime = 'application/octet-stream';
    if (substr($blob, 0, 8) === "\x89PNG\x0D\x0A\x1A\x0A") {
        $mime = 'image/png';
    } elseif (substr($blob, 0, 3) === "\xFF\xD8\xFF") {
        $mime = 'image/jpeg';
    } elseif (substr($blob, 0, 6) === "GIF87a" || substr($blob, 0, 6) === "GIF89a") {
        $mime = 'image/gif';
    }

    // Limpiar buffers previos (si hay output accidental lo eliminamos)
    while (ob_get_level()) ob_end_clean();

    header("Content-Type: {$mime}");
    header("Content-Length: {$len}");
    header("Cache-Control: public, max-age=31536000"); // opcional
    echo $blob;
    exit;
} catch (PDOException $e) {
    // En producción no muestres el mensaje completo; para depuración temporal sí.
    http_response_code(500);
    echo "DB error: " . $e->getMessage();
    exit;
}
