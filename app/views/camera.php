<?php
session_start();

if (!isset(($_SESSION['userId']))) {
    header("Location: /login");
    die();
}
require_once __DIR__ . '/../controllers/PostController.php';

$postController = new PostController();
$response = $postController->getPostsByUser($_SESSION['userId']);

$data = json_decode($response, true);

$posts = $data['posts'];
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
    <title>Camera</title>
</head>

<!-- <pre><?php var_dump($posts); ?></pre> -->

<body class="min-h-screen bg-gradient-to-br from-gray-900 via-purple-900 to-black">
    <?php include __DIR__ . '/templates/main_header.php'; ?>
    <main class="flex">
        <aside class="w-80 bg-black/50 backdrop-blur-sm border-r border-gray-800 p-6 flex flex-col">

            <div class="flex items-center gap-3 mb-8">
                <div>
                    <h1 class="text-xl font-bold text-white">Dark Studio</h1>
                    <p class="text-xs text-gray-400">Create your masterpiece</p>
                </div>
            </div>

            <div class="bg-gray-900/50 border border-gray-800 backdrop-blur-sm mb-6 p-4 rounded">
                <h2 class="flex items-center gap-2 text-white text-lg mb-4">
                    <i class="fas fa-moon text-purple-400"></i>
                    Dark Elements
                </h2>
                <div id="stickers" class="grid grid-cols-4 gap-2 text-xl"></div>
            </div>

            <div class="bg-gray-900/50 border border-gray-800 backdrop-blur-sm mb-6 p-4 rounded">
                <h2 class="flex items-center gap-2 text-white text-lg mb-4">
                    <i class="fas fa-sparkles text-purple-400"></i>
                    Dark Filters
                </h2>
                <div id="filters" class="grid grid-cols-2 gap-2"></div>
            </div>

            <div class="bg-gray-900/50 border border-gray-800 backdrop-blur-sm p-4 rounded mb-6">
                <h2 class="flex items-center gap-2 text-white text-lg mb-4">
                    <i class="fas fa-cog text-purple-400"></i>
                    Adjustments
                </h2>

                <div class="space-y-4">
                    <div>
                        <label for="brightness" class="text-sm text-gray-300 mb-2 block">Brightness</label>
                        <input id="brightness" type="range" min="50" max="200" value="100" class="w-full" />
                        <div id="brightnessValue" class="text-xs text-gray-400 mt-1">100%</div>
                    </div>

                    <div>
                        <label for="contrast" class="text-sm text-gray-300 mb-2 block">Contrast</label>
                        <input id="contrast" type="range" min="50" max="200" value="100" class="w-full" />
                        <div id="contrastValue" class="text-xs text-gray-400 mt-1">100%</div>
                    </div>
                </div>
            </div>

            <div class="bg-gray-900/50 border border-gray-800 backdrop-blur-sm p-4 rounded mb-6">
                <h2 class="flex items-center gap-2 text-white text-lg mb-4">
                    <i class="fas fa-upload text-purple-400"></i>
                    Previous Photos
                </h2>

                <div class="grid grid-cols-3 gap-2" id="previousPhotos">
                    <?php foreach ($posts as $post): ?>
                        <div class="relative aspect-square rounded-lg overflow-hidden border border-gray-700 hover:border-purple-500 cursor-pointer transition-colors bg-gray-800">
                            <img
                                src="/get_image.php?id=<?= $post['id'] ?>"
                                class="w-full h-full object-cover hover:scale-105 transition-transform" />
                        </div>
                    <?php endforeach; ?>
                </div>

            </div>

            <div class="bg-gray-900/50 border border-gray-800 backdrop-blur-sm p-4 rounded">
                <h2 class="flex items-center gap-2 text-white text-lg mb-4">
                    <span class="text-purple-400 text-lg">📝</span>
                    Photo Title
                </h2>

                <input
                    id="photoTitle"
                    type="text"
                    placeholder="Give your photo a title..."
                    maxlength="100"
                    class="w-full px-4 py-2 rounded-lg bg-gray-800 border border-gray-700 text-white placeholder-gray-500 focus:border-purple-500 focus:outline-none transition-colors" />

                <p id="titleLength" class="text-xs text-gray-400 mt-2">0 / 100</p>
            </div>

        </aside>


        <div class="flex-1 p-8 flex flex-col">
            <div
                class="flex-1 bg-gray-900/50 border border-gray-800 backdrop-blur-sm mb-6 rounded p-6 flex flex-col relative overflow-hidden">
                <video id="video" autoplay playsinline class="w-full h-full object-cover rounded-xl"></video>
                <div id="imageContainer" style="position:relative;" class=" flex h-full w-full aling.center justify-center">
                    <img id="capturedImage" src="" style="max-width: 1000px; max-height: 800px;" />
                </div>

                <div id="stickerPreview"
                    class="absolute inset-0 flex items-center justify-center pointer-events-none text-8xl drop-shadow-2xl">
                </div>
                <canvas id="canvas" class="hidden"></canvas>
                <div
                    class="absolute inset-0 bg-gradient-to-t from-black/10 via-transparent to-black/5 pointer-events-none rounded-xl">
                </div>
            </div>

            <div class="flex items-center justify-center gap-4">
                <button id="startCameraBtn"
                    class="bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 text-white border-0 px-8 py-3 rounded flex items-center gap-2">
                    <i class="fas fa-camera"></i> Start Camera
                </button>

                <button id="captureBtn"
                    class="bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 text-white border-0 px-8 py-3 rounded flex items-center gap-2 hidden">
                    <i class="fas fa-bolt"></i> Capture
                </button>

                <div class="relative">
                    <input id="uploadInput" type="file" accept="image/*"
                        class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" />
                    <button
                        class="border border-gray-600 text-gray-300 hover:bg-gray-800 hover:text-white px-6 py-3 rounded flex items-center gap-2">
                        <i class="fas fa-upload"></i> Upload
                    </button>
                </div>

                <button id="retakeBtn"
                    class="border border-gray-600 text-gray-300 hover:bg-gray-800 hover:text-white px-6 py-3 rounded flex items-center gap-2 hidden">
                    <i class="fas fa-rotate-left"></i> Retake
                </button>

                <button id="downloadBtn"
                    class="bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 text-white border-0 px-6 py-3 rounded flex items-center gap-2 hidden">
                    <i class="fas fa-download"></i> Download
                </button>

                <button id="shareBtn"
                    class="bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 text-white border-0 px-8 py-3 rounded hidden">
                    Share to Gallery
                </button>
            </div>
        </div>
    </main>
    <?php include __DIR__ . '/templates/footer.php'; ?>
    <script>
        window.USER_ID = <?= json_encode($_SESSION['userId']) ?>;
    </script>
    <script src="/js/alert.js"></script>
    <script src="/js/camera.js"></script>
</body>

</html>