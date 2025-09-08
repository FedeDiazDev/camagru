<?php
session_start();

// if (!isset(($_SESSION['userId']))) {
//     header("Location: /");
//     die();
// }

require_once __DIR__ . '/../../controllers/PostController.php';

if ($_SERVER["REQUEST_METHOD"] === "GET")
{
    $postControl = new PostController();
    $dataPost = json_decode($postControl->getPostById($_GET['id']));
    // echo $dataPost;
    // echo "ID:::::" . $_GET['id'];
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Post Detail</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.0/css/all.min.css"
        integrity="sha512-DxV+EoADOkOygM4IR9yXP8Sb2qwgidEmeqAEmDKIOfPRQZOWbXCzLC6vjbZyy0vPisbH2SyW27+ddLVCN+OMzQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body class="min-h-screen bg-gradient-to-br from-gray-900 via-purple-900 to-black">
    <header class="border-b border-gray-800 bg-black/50 backdrop-blur-xl sticky top-0 z-50">
        <div class="container mx-auto px-4 py-4 flex items-center justify-between">
            <div class="flex items-center gap-4">
                <a href="/gallery" class="p-2 rounded-full text-gray-400 hover:text-white hover:bg-gray-800">
                    <i class="fa-solid fa-arrow-left w-5 h-5"></i>
                </a>
                <div class="flex items-center gap-2">
                    <i class="fa-solid fa-camera text-purple-400 text-xl"></i>
                    <h1
                        class="text-lg lg:text-xl font-bold bg-gradient-to-r from-purple-400 to-pink-400 bg-clip-text text-transparent">
                        Photo Details
                    </h1>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <button class="p-2 rounded-full text-gray-400 hover:text-white hover:bg-gray-800">
                    <i class="fa-regular fa-bookmark"></i>
                </button>
                <button class="p-2 rounded-full text-gray-400 hover:text-white hover:bg-gray-800">
                    <i class="fa-solid fa-share"></i>
                </button>
            </div>
        </div>
    </header>

    <div class="container mx-auto px-4 py-6 max-w-6xl">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2">
                <div class="bg-gray-900/50 border border-gray-800 backdrop-blur-sm overflow-hidden rounded-2xl">
                    <div class="aspect-square relative">
                        <img src="https://fastly.picsum.photos/id/965/400/400.jpg?hmac=KAYbpNyKTmK5tp3_iz11PxtITzVZlHlJ8v3k90vG_hI"
                            alt="Post image" class="w-full h-full object-cover" />

                        <div
                            class="absolute bottom-0 left-0 right-0 h-32 bg-gradient-to-t from-black/80 via-black/20 to-transparent">
                        </div>
                    </div>
                </div>

                <div class="lg:hidden mt-4">
                    <div class="bg-gray-900/50 border border-gray-800 backdrop-blur-sm rounded-2xl">
                        <div class="p-4">
                            <div class="flex items-center justify-between mb-4">
                                <div class="flex items-center gap-3">
                                    <img src="https://fastly.picsum.photos/id/965/400/400.jpg?hmac=KAYbpNyKTmK5tp3_iz11PxtITzVZlHlJ8v3k90vG_hI"
                                        alt="" class="w-12 h-12 rounded-full border-2 border-purple-500/30" />
                                    <div>
                                        <p class="font-semibold text-white">shadow_master</p>
                                        <div class="flex items-center gap-2 text-gray-400 text-sm">
                                            <i class="fa-regular fa-clock text-xs"></i>
                                            <span>2 hours ago</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="flex items-center justify-between mb-4">
                                <div class="flex items-center gap-4">
                                    <button class="p-2 text-gray-400 hover:text-pink-400 hover:bg-gray-800 rounded-lg">
                                        <i class="fa-regular fa-heart mr-2"></i>
                                        <span class="font-medium">2,847</span>
                                    </button>
                                    <button
                                        class="p-2 text-gray-400 hover:text-purple-400 hover:bg-gray-800 rounded-lg">
                                        <i class="fa-regular fa-comment mr-2"></i>
                                        <span class="font-medium">4</span>
                                    </button>
                                </div>
                            </div>

                            <div class="space-y-3">
                                <p class="text-gray-300 leading-relaxed">
                                    Lost in the shadows of the night 🌙 This piece represents
                                    the eternal dance between light and darkness. Created with
                                    vintage film techniques and enhanced with digital magic.
                                    #DarkArt #Photography #Shadows #NightVibes
                                </p>

                                <div class="flex flex-wrap gap-2">
                                    <span
                                        class="text-purple-400 text-sm hover:text-purple-300 cursor-pointer">#DarkArt</span>
                                    <span
                                        class="text-purple-400 text-sm hover:text-purple-300 cursor-pointer">#Photography</span>
                                    <span
                                        class="text-purple-400 text-sm hover:text-purple-300 cursor-pointer">#Shadows</span>
                                    <span
                                        class="text-purple-400 text-sm hover:text-purple-300 cursor-pointer">#NightVibes</span>
                                </div>

                                <p class="text-gray-500 text-sm">
                                    📍 Dark Studio, Barcelona
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="hidden lg:block">
                <div class="sticky top-24 space-y-6">
                    <div class="bg-gray-900/50 border border-gray-800 backdrop-blur-sm rounded-2xl">
                        <div class="p-6">
                            <div class="flex items-center justify-between mb-6">
                                <div class="flex items-center gap-3">
                                    <img src="https://placehold.co/600x600/png" alt=""
                                        class="w-12 h-12 rounded-full border-2 border-purple-500/30" />
                                    <div>
                                        <p class="font-semibold text-white">shadow_master</p>
                                        <div class="flex items-center gap-2 text-gray-400 text-sm">
                                            <i class="fa-regular fa-clock text-xs"></i>
                                            <span>2 hours ago</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="flex items-center justify-between mb-6">
                                <div class="flex items-center gap-4">
                                    <button class="p-2 text-gray-400 hover:text-pink-400 hover:bg-gray-800 rounded-lg">
                                        <i class="fa-regular fa-heart mr-2"></i>
                                        <span class="font-medium">2,847</span>
                                    </button>
                                    <button
                                        class="p-2 text-gray-400 hover:text-purple-400 hover:bg-gray-800 rounded-lg">
                                        <i class="fa-regular fa-comment mr-2"></i>
                                        <span class="font-medium">4</span>
                                    </button>
                                </div>
                            </div>

                            <div class="space-y-4">
                                <p class="text-gray-300 leading-relaxed">
                                    Lost in the shadows of the night 🌙 This piece represents
                                    the eternal dance between light and darkness. Created with
                                    vintage film techniques and enhanced with digital magic.
                                </p>

                                <div class="flex flex-wrap gap-2">
                                    <span
                                        class="text-purple-400 text-sm hover:text-purple-300 cursor-pointer">#DarkArt</span>
                                    <span
                                        class="text-purple-400 text-sm hover:text-purple-300 cursor-pointer">#Photography</span>
                                    <span
                                        class="text-purple-400 text-sm hover:text-purple-300 cursor-pointer">#Shadows</span>
                                    <span
                                        class="text-purple-400 text-sm hover:text-purple-300 cursor-pointer">#NightVibes</span>
                                </div>

                                <p class="text-gray-500 text-sm">
                                    📍 Dark Studio, Barcelona
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-6">
            <div class="bg-gray-900/50 border border-gray-800 backdrop-blur-sm rounded-2xl">
                <div class="p-6">
                    <h3 class="text-xl font-bold text-white mb-6 flex items-center gap-2">
                        <!-- TODO:  COMENTARIOS-->
                        <i class="fa-regular fa-comment text-purple-400"></i> Comments (nº)
                    </h3>

                    <div class="mb-6">
                        <div class="flex gap-3">
                            <img src="https://placehold.co/600x600/png" alt=""
                                class="w-10 h-10 rounded-full border-2 border-purple-500/30" />
                            <div class="flex-1">
                                <textarea placeholder="Add a comment..."
                                    class="w-full bg-gray-800 border border-gray-700 text-white placeholder-gray-500 rounded-lg p-3 min-h-[80px]"></textarea>
                                <div class="flex justify-between items-center mt-3">
                                    <p class="text-gray-500 text-sm">
                                        Press Enter to post, Shift+Enter for new line
                                    </p>
                                    <button
                                        class="px-4 py-2 bg-gradient-to-r from-purple-600 to-pink-600 rounded-lg text-white">
                                        <i class="fa-solid fa-paper-plane mr-2"></i> Post
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-6">
                        <div class="flex gap-3">
                            <img src="https://placehold.co/600x600/png" alt=""
                                class="w-10 h-10 rounded-full border-2 border-gray-700" />
                            <div class="flex-1 min-w-0">
                                <div class="bg-gray-800/50 rounded-lg p-4">
                                    <div class="flex items-center justify-between mb-2">
                                        <div class="flex items-center gap-2">
                                            <p class="font-semibold text-white text-sm">
                                                dark_photographer
                                            </p>
                                        </div>
                                        <span class="text-gray-500 text-xs">1 hour ago</span>
                                    </div>
                                    <p class="text-gray-300 leading-relaxed">
                                        Absolutely stunning work! The contrast between light and
                                        shadow is perfect.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>