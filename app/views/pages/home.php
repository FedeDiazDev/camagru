<?php
session_start();
require_once __DIR__ . '/../../controllers/PostController.php';
$postController = new PostController();
$postsJson = $postController->getPosts(12, 0);
$postsData = json_decode($postsJson);
$recentPosts = ($postsData && $postsData->res) ? $postsData->msg : [];
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
    <title>Document</title>
</head>

<body>
    <div class="min-h-screen bg-gradient-to-br from-gray-900 via-purple-900 to-black flex">
        <?php include __DIR__ . '/../templates/header.php'; ?>
        <div class="min-h-screen flex flex-col w-full">
            <main class="flex-1 flex flex-col">
                <div class="flex-1 overflow-y-auto">
                    <section class="p-8 pb-0">
                        <div class="max-w-4xl">
                            <div class="flex items-center gap-2 mb-4">
                                🌙
                                <span class="text-purple-300 text-sm font-medium">Welcome to your creative space</span>
                            </div>
                            <h2 class="text-5xl font-bold text-white mb-4 leading-tight">
                                Where ideas come to
                                <span class="bg-gradient-to-r from-purple-400 to-pink-400 bg-clip-text text-transparent">
                                    life
                                </span>
                            </h2>
                            <p class="text-xl text-gray-300 mb-8 max-w-2xl">
                                Bring your vision to reality with our powerful tools. Capture, edit, and share moments that inspire and brighten your world. </p>
                            <a href="/camera" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 text-white font-semibold rounded border-0">
                                ⚡
                                <span class="ml-2">Start Creating Now</span>
                            </a>
                        </div>
                    </section>
                    <section class="p-8">
                        <div class="grid grid-cols-12 gap-6">
                            <div class="col-span-8 bg-gray-900/50 border border-gray-800 backdrop-blur-sm overflow-hidden group cursor-pointer hover:shadow-2xl hover:shadow-purple-500/20 transition-all duration-300 rounded">
                                <div class="aspect-[16/10] relative">
                                    <img
                                        src="https://images.unsplash.com/photo-1492684223066-81342ee5ff30?auto=format&fit=crop&w=800&q=80"
                                        alt="Featured Dark Photo"
                                        class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" />
                                    <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent"></div>
                                    <div class="absolute bottom-6 left-6 right-6 text-white">
                                        <div class="flex items-center gap-3 mb-3">
                                            <div class="w-10 h-10 rounded-full border-2 border-white/20 overflow-hidden bg-gray-800 flex items-center justify-center text-white font-semibold">
                                                DA
                                            </div>
                                            <div>
                                                <p class="font-semibold">@dark_artist</p>
                                                <p class="text-gray-300 text-sm">Featured Creator</p>
                                            </div>
                                        </div>
                                        <h3 class="text-2xl font-bold mb-2">Shadows of the Night</h3>
                                        <p class="text-gray-300 mb-4">
                                            A masterpiece that captures the essence of darkness and light intertwining in perfect harmony.
                                        </p>
                                        <div class="flex items-center gap-6">
                                            <div class="flex items-center gap-2 text-pink-400">
                                                ❤️
                                                <span class="font-medium">2.4k</span>
                                            </div>
                                            <div class="flex items-center gap-2 text-purple-400">
                                                💬
                                                <span class="font-medium">89</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Trending Section -->
                            <div class="col-span-4 space-y-6">
                                <div class="bg-gray-900/50 border border-gray-800 backdrop-blur-sm rounded p-4">
                                    <div class="flex items-center gap-2 mb-4 text-white font-semibold text-lg">
                                        📈 Trending Now
                                    </div>
                                    <div class="space-y-4">
                                        <div class="flex items-center gap-3 group cursor-pointer">
                                            <div class="w-12 h-12 rounded-lg overflow-hidden bg-gray-800">
                                                <img
                                                    src="https://images.unsplash.com/photo-1439792675105-701e6a4ab6f0?q=80&w=1173&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D"
                                                    alt="Trending 1"
                                                    class="w-full h-full object-cover group-hover:scale-110 transition-transform" />
                                            </div>
                                            <div class="flex-1">
                                                <p class="text-white text-sm font-medium">Dark Vision #1</p>
                                                <p class="text-gray-400 text-xs">@shadow_user1</p>
                                            </div>
                                            <div class="text-purple-400 text-sm font-medium">237</div>
                                        </div>
                                        <div class="flex items-center gap-3 group cursor-pointer">
                                            <div class="w-12 h-12 rounded-lg overflow-hidden bg-gray-800">
                                                <img
                                                    src="https://images.unsplash.com/photo-1475070929565-c985b496cb9f?q=80&w=1170&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D"
                                                    alt="Trending 2"
                                                    class="w-full h-full object-cover group-hover:scale-110 transition-transform" />
                                            </div>
                                            <div class="flex-1">
                                                <p class="text-white text-sm font-medium">Dark Vision #2</p>
                                                <p class="text-gray-400 text-xs">@shadow_user2</p>
                                            </div>
                                            <div class="text-purple-400 text-sm font-medium">412</div>
                                        </div>
                                        <div class="flex items-center gap-3 group cursor-pointer">
                                            <div class="w-12 h-12 rounded-lg overflow-hidden bg-gray-800">
                                                <img
                                                    src="https://images.unsplash.com/photo-1504903271097-d7e7c7f5f7ad?q=80&w=880&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D"
                                                    alt="Trending 3"
                                                    class="w-full h-full object-cover group-hover:scale-110 transition-transform" />
                                            </div>
                                            <div class="flex-1">
                                                <p class="text-white text-sm font-medium">Dark Vision #3</p>
                                                <p class="text-gray-400 text-xs">@shadow_user3</p>
                                            </div>
                                            <div class="text-purple-400 text-sm font-medium">154</div>
                                        </div>
                                    </div>
                                </div>

                                <div class="bg-gray-900/50 border border-gray-800 backdrop-blur-sm rounded p-4">
                                    <div class="mb-2 text-white font-semibold text-lg">Quick Create</div>
                                    <div class="text-gray-400 mb-4">Jump into creation mode</div>
                                    <a href="/camera" class="block w-full mb-3 px-4 py-2 bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 text-white text-center rounded font-semibold">
                                        📷 Open Camera
                                    </a>
                                    <a href="/gallery" class="block w-full px-4 py-2 border border-gray-600 text-gray-300 hover:bg-gray-800 hover:text-white text-center rounded font-semibold bg-transparent">
                                        👥 Browse Gallery
                                    </a>
                                </div>
                            </div>

                            <div class="col-span-12">
                                <div class="flex items-center justify-between mb-6">
                                    <h3 class="text-2xl font-bold text-white">
                                        Recent <span class="text-purple-400">Creations</span>
                                    </h3>
                                    <a href="/gallery" class="border border-gray-600 text-gray-300 hover:bg-gray-800 hover:text-white bg-transparent px-3 py-1 rounded text-sm font-semibold">
                                        View All
                                    </a>
                                </div>

                                <div class="grid grid-cols-6 gap-4">
                                    <?php if (empty($recentPosts)): ?>
                                        <div class="col-span-6 flex flex-col items-center justify-center py-12 text-center bg-gray-900/30 border border-gray-800 rounded-lg">
                                            <div class="text-4xl mb-4">📸</div>
                                            <h3 class="text-xl font-bold text-white mb-2">No photos yet</h3>
                                            <p class="text-gray-400 mb-4">Be the first to capture a moment!</p>
                                            <a href="/camera" class="px-4 py-2 bg-gradient-to-r from-purple-600 to-pink-600 rounded text-white font-medium hover:from-purple-700 hover:to-pink-700 transition">
                                                Go to Camera
                                            </a>
                                        </div>
                                    <?php else: ?>
                                        <?php foreach ($recentPosts as $post): ?>
                                            <a href="/post?id=<?= $post->id ?>" class="block overflow-hidden group cursor-pointer hover:shadow-xl hover:shadow-purple-500/20 transition-all duration-300 bg-gray-900/30 border border-gray-800 rounded">
                                                <div class="aspect-square relative">
                                                    <img
                                                        src="<?= htmlspecialchars($post->mediaUrl) ?>"
                                                        alt="User Creation"
                                                        class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500" />
                                                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                                                    <div class="absolute bottom-2 left-2 right-2 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                                        <div class="flex items-center justify-between text-white text-xs">
                                                            <div class="flex items-center gap-1">
                                                                ❤️
                                                                <span><?= htmlspecialchars($post->likes ?? 0) ?></span>
                                                            </div>
                                                            <div class="flex items-center gap-1">
                                                                💬
                                                                <span><?= htmlspecialchars($post->comments ?? 0) ?></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </a>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>
            </main>
            <?php include __DIR__ . '/../templates/footer.php'; ?>
        </div>
    </div>

</body>

</html>