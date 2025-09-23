<?php

require_once __DIR__ . '/../controllers/PostController.php';
if ($_SERVER["REQUEST_METHOD"] === "GET") {
    $postController = new PostController();
    $posts = $postController->getPosts(5, 0);
    $data = json_decode($posts);
    echo $posts;
    $mediaUrl = $data->msg[0]->mediaUrl;
    // echo $mediaUrl;
}
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
        <p class="text-gray-400">42 photos found</p>
    </header>

<main>
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        <?php if (!empty($data->msg)): ?>
            <?php foreach ($data->msg as $post): ?>
                <div
                    class="overflow-hidden group hover:shadow-2xl hover:shadow-purple-500/20 transition-all duration-300 bg-gray-900/50 border border-gray-800 backdrop-blur-sm rounded-lg">
                    
                    <!-- enlace al post -->
                    <a href="/post?id=<?= htmlspecialchars($post->id) ?>" class="aspect-square block relative overflow-hidden rounded-lg">
                        <img src="<?= htmlspecialchars($post->mediaUrl) ?>" alt="Foto"
                            class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500" />
                        <div
                            class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 rounded-lg">
                        </div>
                        <div
                            class="absolute bottom-4 left-4 right-4 opacity-0 group-hover:opacity-100 transition-opacity duration-300 text-white">
                            <div class="flex items-center gap-2 mb-2">
                                <div
                                    class="w-6 h-6 rounded-full border border-white/20 bg-gray-700 overflow-hidden flex items-center justify-center">
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
        <?php else: ?>
            <p class="text-gray-400">No se encontraron fotos</p>
        <?php endif; ?>
    </div>
</main>



</body>

</html>