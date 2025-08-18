<?php
session_start();

if (isset($_SESSION['userId'])) {
    header("Location: /camera");
    die();
}
require_once __DIR__ . '/../controllers/UserController.php';
$userController = new UserController();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim(htmlspecialchars($_POST['username']));
    $password = htmlspecialchars($_POST['password']);

    $res = $userController->logIn($username, $password);
    if ($res['res']) {
        $_SESSION['userId'] = $res['msg']['id'];
        $_SESSION['username'] = $res['msg']['username'];
    }
    echo json_encode($res);
    exit;
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
    <title>Login</title>
</head>

<body>
    <div class="min-h-screen bg-gradient-to-br from-gray-900 via-purple-900 to-black flex">
        <!-- Left Side - Branding -->
        <div class="hidden lg:flex lg:w-1/2 relative overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-br from-purple-600/20 to-pink-600/20"></div>
            <img
                src="https://placehold.co/600x400"
                alt="Dark Photography"
                class="w-full h-full object-cover" />
            <div class="absolute inset-0 bg-gradient-to-t from-black via-black/50 to-transparent"></div>
            <div class="absolute bottom-12 left-12 right-12">
                <div class="flex items-center gap-3 mb-6">
                    <i class="fas fa-camera-retro text-purple-400 text-3xl"></i>
                    <div>
                        <h1 class="text-4xl font-bold bg-gradient-to-r from-purple-400 to-pink-400 bg-clip-text text-transparent">
                            Camagru <span class="ml-1 text-yellow-300">🌙</span>
                        </h1>
                        <p class="text-gray-300">Welcome to your creative space</p>
                    </div>
                </div>
                <blockquote class="text-2xl text-white font-light leading-relaxed">
                    Where ideas come to life — bring your vision to reality with our powerful tools.
                    Capture, edit, and share moments that inspire and brighten your world.
                </blockquote>
                <p class="text-gray-400 mt-4">— Your Creative Community</p>
            </div>
        </div>

        <div class="w-full lg:w-1/2 flex items-center justify-center p-8">
            <div class="w-full max-w-md">
                <!-- Back Button -->
                <button onclick="location.href='/'" class="mb-8 text-gray-400 hover:text-white hover:bg-gray-800 flex items-center gap-2 px-3 py-2 rounded">
                    <i class="fas fa-arrow-left"></i>
                    <span>Back to Home</span>
                </button>

                <section class="bg-gray-900/80 border border-gray-800 rounded-lg backdrop-blur-sm p-8">
                    <header class="text-center pb-8">
                        <div class="lg:hidden flex items-center justify-center gap-2 mb-6">
                            <i class="fas fa-camera-retro text-purple-400 text-2xl"></i>
                            <h1 class="text-2xl font-bold bg-gradient-to-r from-purple-400 to-pink-400 bg-clip-text text-transparent">
                                In <span class="ml-1 text-yellow-300">🌙</span>
                            </h1>
                        </div>
                        <h2 class="text-3xl text-white mb-2 font-semibold">Welcome back</h2>
                        <p class="text-gray-400 text-base">
                            Step into your creative space and continue bringing ideas to life.
                        </p>
                    </header>

                    <form id= "form" method="post" action="/login" class="space-y-6">
                        <div class="space-y-1">
                            <label for="text" class="block text-gray-300 text-sm font-medium">
                                Email Address
                            </label>
                            <input
                                id="username"
                                type="username"
                                name="username"
                                 value="<?= htmlspecialchars($_POST['username'] ?? '') ?>"
                                placeholder="Username"
                                required
                                class="w-full h-12 bg-gray-800 border border-gray-700 rounded px-3 text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500" />
                        </div>

                        <div class="space-y-1 relative">
                            <label for="password" class="block text-gray-300 text-sm font-medium">
                                Password
                            </label>
                            <input
                                id="password"
                                type="password"
                                name="password"
                                placeholder="Enter your password"
                                required
                                class="w-full h-12 bg-gray-800 border border-gray-700 rounded px-3 pr-12 text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500" />
                            <button
                                type="button"
                                class="absolute right-3 top-1/2 -translate-y-1/2 h-8 w-8 text-gray-400 hover:text-white"
                                aria-label="Toggle password visibility">
                            </button>
                        </div>

                        <div class="flex items-center justify-between text-sm text-gray-400">
                            <label class="flex items-center space-x-2">
                                <input
                                    type="checkbox"
                                    class="rounded border-gray-600 bg-gray-800 text-purple-600 focus:ring-purple-500" />
                                <span>Remember me</span>
                            </label>
                            <a href="/forgot-password" class="text-purple-400 hover:text-purple-300 transition-colors">
                                Forgot password?
                            </a>
                        </div>

                        <button
                            type="submit"
                            class="w-full h-12 bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 text-white font-medium rounded flex items-center justify-center gap-2">
                            <i class="fas fa-sparkles"></i>
                            Sign In
                        </button>
                    </form>

                    <div class="relative my-6">
                        <div class="absolute inset-0 flex items-center" aria-hidden="true">
                            <div class="w-full border-t border-gray-700"></div>
                        </div>
                        <div class="relative flex justify-center text-sm text-gray-400">
                            <span class="bg-gray-900 px-4">or</span>
                        </div>
                    </div>

                    <p class="text-center text-gray-400 text-sm">
                        New to the space?
                        <a href="/register" class="text-purple-400 hover:text-purple-300 transition-colors font-medium">
                            Create an account
                        </a>
                    </p>
                </section>
            </div>
        </div>
        <script>
            const form = document.getElementById("form");
            form.addEventListener("submit", async (event) => {
                event.preventDefault();

                const formData = new FormData(form);
                const response = await fetch(form.action, {
                    method: "POST",
                    body: formData
                });
                const result = await response.json();
                console.log(result);
                if (!result.res) { //TODO:: pintar eerror en fron
                    console.log(result.errors.map((error) => `<p>${error}</p>`).join(""));
                } else {
                    window.location.href = "/camera";
                }
            });
        </script>
</body>

</html>