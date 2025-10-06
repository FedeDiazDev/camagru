<?php
require_once __DIR__ . '/../controllers/PostController.php';

$postController = new PostController();

$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 6;
$offset = ($page - 1) * $limit;
$response = json_decode($postController->getPosts($limit, $offset));
$posts = isset($response->msg) && is_array($response->msg) ? $response->msg : [];
$totalPosts = $postController->countPosts();
$totalPages = ceil($totalPosts / $limit);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.0/css/all.min.css" 
        integrity="sha512-DxV+EoADOkOygM4IR9yXP8Sb2qwgidEmeqAEmDKIOfPRQZOWbXCzLC6vjbZyy0vPisbH2SyW27+ddLVCN+OMzQ==" 
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <title>Gallery</title>
</head>
<body class="min-h-screen bg-gradient-to-br from-gray-900 via-purple-900 to-black p-6">

    <a href="/" class="inline-flex items-center text-gray-400 hover:text-white hover:bg-gray-800 p-2 rounded mb-6">
        <i class="fas fa-arrow-left w-5 h-5"></i>
    </a>

    <header class="mb-8">
        <h2 class="text-3xl font-bold text-white mb-2">Discover</h2>
        <p class="text-gray-400">
            <?= $totalPosts > 0 ? "$totalPosts photos found" : "No photos yet" ?>
        </p>
    </header>

    <main>
        <?php if (count($posts) > 0): ?>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-3 gap-6">
                <?php foreach ($posts as $post): ?>
                    <div class="overflow-hidden group hover:shadow-2xl hover:shadow-purple-500/20 transition-all duration-300 bg-gray-900/50 border border-gray-800 backdrop-blur-sm rounded-lg">
                        <a href="/post?id=<?= htmlspecialchars($post->id) ?>" class="aspect-square block relative overflow-hidden rounded-lg">
                            <img src="<?= htmlspecialchars($post->mediaUrl) ?>" alt="Foto"
                                class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500" />
                            <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 rounded-lg"></div>
                            <div class="absolute bottom-4 left-4 right-4 opacity-0 group-hover:opacity-100 transition-opacity duration-300 text-white">
                                <div class="flex items-center gap-2 mb-2">
                                    <div class="w-6 h-6 rounded-full border border-white/20 bg-gray-700 overflow-hidden flex items-center justify-center">
                                        <img src="<?= htmlspecialchars($post->avatarUrl ?? 'https://placehold.co/24x24/png') ?>" alt="Avatar"
                                            class="w-full h-full object-cover" />
                                    </div>
                                    <span class="text-sm font-medium"><?= htmlspecialchars($post->author ?? 'unknown') ?></span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-3">
                                        <div class="flex items-center gap-1 text-pink-400">
                                            <i class="fas fa-heart w-4 h-4"></i>
                                            <span class="text-sm"><?= htmlspecialchars($post->likes ?? 0) ?></span>
                                        </div>
                                        <div class="flex items-center gap-1 text-purple-400">
                                            <i class="fas fa-comment-dots w-4 h-4"></i>
                                            <span class="text-sm"><?= htmlspecialchars($post->comments ?? 0) ?></span>
                                        </div>
                                    </div>
                                    <span class="text-gray-300 text-xs"><?= htmlspecialchars($post->createdAt ?? '') ?></span>
                                </div>
                            </div>
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>

            <?php if ($totalPages > 1): ?>
                <div class="flex justify-center mt-10 space-x-2">
                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <a href="?page=<?= $i ?>" 
                            class="px-4 py-2 rounded-lg border <?= $i === $page ? 'bg-purple-600 text-white border-purple-500' : 'border-gray-700 text-gray-300 hover:bg-gray-800' ?>">
                            <?= $i ?>
                        </a>
                    <?php endfor; ?>
                </div>
            <?php endif; ?>

        <?php else: ?>
            <p class="text-gray-400 text-center mt-20 text-lg">No se encontraron fotos.</p>
        <?php endif; ?>
    </main>

</body>
</html>
