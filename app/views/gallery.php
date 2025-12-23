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

<body class="min-h-screen bg-gradient-to-br from-gray-900 via-purple-900 to-black">

    <?php include __DIR__ . '/templates/main_header.php'; ?>
    <main class="p-6">
        <?php if (count($posts) > 0): ?>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-3 gap-6">
                <?php foreach ($posts as $post): ?>
                    <div class="overflow-hidden group hover:shadow-2xl hover:shadow-purple-500/20 transition-all duration-300 bg-gray-900/50 border border-gray-800 backdrop-blur-sm rounded-lg">
                        <a href="/post?id=<?= htmlspecialchars($post->id) ?>" class="aspect-square block relative overflow-hidden rounded-lg">
                            <img src="<?= htmlspecialchars($post->mediaUrl) ?>" alt="Post image"
                                class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500" />
                            <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 rounded-lg"></div>
                            <div class="absolute bottom-4 left-4 right-4 opacity-0 group-hover:opacity-100 transition-opacity duration-300 text-white">
                                <div class="flex items-center gap-2 mb-2">
                                    <div class="w-6 h-6 rounded-full border border-white/20 bg-gray-700 overflow-hidden flex items-center justify-center">
                                        <img src="<?= htmlspecialchars($post->avatarUrl ?? 'https://i.pravatar.cc/150?u=a042581f4e29026704c') ?>" alt="Avatar"
                                            class="w-full h-full object-cover" />
                                    </div>
                                    <span class="text-sm font-medium"><?= htmlspecialchars($post->author ?? 'unknown') ?></span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-3">
                                        <div class="flex items-center gap-1 text-pink-400">
                                            <i class="fas fa-heart w-4 h-4"></i>
                                            <span class="text-sm post-<?= htmlspecialchars($post->id) ?>-likes"><?= htmlspecialchars($post->likes ?? 0) ?></span>
                                        </div>
                                        <div class="flex items-center gap-1 text-purple-400">º
                                            <i class="fas fa-comment-dots w-4 h-4"></i>
                                            <span class="text-sm post-<?= htmlspecialchars($post->id) ?>-comments"><?= htmlspecialchars($post->comments ?? 0) ?></span>
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
            <p class="text-gray-400 text-center mt-20 text-lg">Not photos yet.</p>
        <?php endif; ?>
    </main>
    <?php include __DIR__ . '/templates/footer.php'; ?>

    <script>
        function updateGallery() {
            const urlParams = new URLSearchParams(window.location.search);
            const page = urlParams.get('page') || 1;

            fetch(`/api/posts?page=${page}`)
                .then(response => response.json())
                .then(data => {
                    if (data.res && Array.isArray(data.msg)) {
                        data.msg.forEach(post => {
                            // Update likes
                            const likeCount = document.querySelector(`.post-${post.id}-likes`);
                            if (likeCount) likeCount.textContent = post.likes;

                            // Update comments
                            const commentCount = document.querySelector(`.post-${post.id}-comments`);
                            if (commentCount) commentCount.textContent = post.comments;
                        });
                    }
                })
                .catch(error => console.error('Error updating gallery:', error));
        }

        // Poll every 5 seconds
        setInterval(updateGallery, 5000);
    </script>
</body>

</html>