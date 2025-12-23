<?php
session_start();

if (isset($_SESSION['userId'])) {
    header("Location: /camera");
    die();
}
require_once __DIR__ . '/../controllers/UserController.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim(htmlspecialchars($_POST['username']));
    $email = trim(htmlspecialchars($_POST['email']));
    $password = htmlspecialchars($_POST['password']);
    $confirmPassword = htmlspecialchars($_POST['confirmPassword']);

    $userController = new UserController();
    $res = $userController->register($username, $email, $password, $confirmPassword);
    // header('Content-Type: application/json');
    echo $res;
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
    <title>Register</title>
</head>

<body>
    <div class="min-h-screen bg-gradient-to-br from-gray-900 via-purple-900 to-black flex">
        <div class="w-full lg:w-1/2 flex items-center justify-center p-8">
            <div class="w-full max-w-md">
                <a href="/" class="mb-8 inline-flex items-center text-gray-400 hover:text-white hover:bg-gray-800 px-3 py-2 rounded cursor-pointer">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                    </svg>
                    Back to Home
                </a>

                <div class="bg-gray-900/80 border border-gray-800 backdrop-blur-sm rounded-md shadow-md">
                    <div class="text-center pb-6 px-6 pt-8">
                        <div class="lg:hidden flex items-center justify-center gap-2 mb-6">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h2l2-3 3 3h8l-5 6-5-6z" />
                            </svg>
                            <h1 class="text-2xl font-bold bg-gradient-to-r from-purple-400 to-pink-400 bg-clip-text text-transparent select-none">
                                Camagru
                            </h1>
                        </div>
                        <h2 class="text-3xl text-white mb-2 font-semibold">Welcome to your creative space</h2>
                        <p class="text-gray-400 text-base">
                            Where ideas come to life. Bring your vision to reality with our powerful tools.
                        </p>
                    </div>

                    <div class="space-y-5 px-6 pb-8">
                        <form id="form" method="post" action="/register" class=" space-y-5">
                            <div class="space-y-2">
                                <label for="username" class="text-gray-300 text-sm font-medium block">Username</label>
                                <input
                                    id="username"
                                    name="username"
                                    type="text"
                                    placeholder="Choose your shadow name"
                                    value="<?= htmlspecialchars($_POST['username'] ?? '') ?>"
                                    class="h-11 bg-gray-800 border border-gray-700 text-white placeholder-gray-500 focus:border-purple-500 focus:ring-purple-500/20 rounded px-3 w-full"
                                    required />
                            </div>

                            <div class="space-y-2">
                                <label for="email" class="text-gray-300 text-sm font-medium block">Email Address</label>
                                <input
                                    id="email"
                                    name="email"
                                    type="email"
                                    placeholder="your@email.com"
                                    value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
                                    class="h-11 bg-gray-800 border border-gray-700 text-white placeholder-gray-500 focus:border-purple-500 focus:ring-purple-500/20 rounded px-3 w-full"
                                    required />
                            </div>

                            <div class="space-y-2 relative">
                                <label for="password" class="text-gray-300 text-sm font-medium block">Password</label>
                                <input
                                    id="password"
                                    name="password"
                                    type="password"
                                    placeholder="Create a strong password"
                                    class="h-11 bg-gray-800 border border-gray-700 text-white placeholder-gray-500 focus:border-purple-500 focus:ring-purple-500/20 rounded px-3 w-full pr-12"
                                    required />
                            </div>

                            <div class="space-y-2 relative">
                                <label for="confirmPassword" class="text-gray-300 text-sm font-medium block">Confirm Password</label>
                                <input
                                    id="confirmPassword"
                                    name="confirmPassword"
                                    type="password"
                                    placeholder="Confirm your password"
                                    class="h-11 bg-gray-800 border border-gray-700 text-white placeholder-gray-500 focus:border-purple-500 focus:ring-purple-500/20 rounded px-3 w-full pr-12"
                                    required />
                            </div>

                            <button
                                type="submit"
                                class="w-full h-11 bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 text-white border-0 text-base font-medium rounded">
                                <svg xmlns="http://www.w3.org/2000/svg" class="inline w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                </svg>
                                Create Account
                            </button>
                        </form>

                        <div class="text-center mt-6">
                            <p class="text-gray-400">
                                Already creating magic?
                                <a href="/login" class="text-purple-400 hover:text-purple-300 transition-colors font-medium ml-1">
                                    Sign in
                                </a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="hidden lg:flex lg:w-1/2 relative overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-bl from-purple-600/20 to-pink-600/20"></div>
            <img
                src="https://images.unsplash.com/photo-1431440869543-efaf3388c585?q=80&w=2070&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D"
                alt="Dark Photography Community"
                class="w-full h-full object-cover" />
            <div class="absolute inset-0 bg-gradient-to-t from-black via-black/50 to-transparent"></div>
            <div class="absolute bottom-12 left-12 right-12">
                <h2 class="text-3xl font-bold text-white mb-8">Join our community</h2>
                <div class="space-y-4">
                    <?php
                    $benefits = [
                        "Capture moments that inspire",
                        "Edit with powerful, intuitive tools",
                        "Connect with a community of creators",
                        "Share your creations and brighten your world"
                    ];
                    foreach ($benefits as $benefit) : ?>
                        <div class="flex items-center gap-3">
                            <div class="w-6 h-6 bg-gradient-to-r from-purple-500 to-pink-500 rounded-full flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                </svg>
                            </div>
                            <span class="text-gray-300"><?= htmlspecialchars($benefit) ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
    <?php include __DIR__ . '/templates/footer.php'; ?>
    <script src="/js/alert.js"></script>
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
            // console.log(result);
            if (!result.res) {
                showAlert(result.msg, "error");
            } else {
                showAlert("Verifica tu cuenta en el email", "success");
                setTimeout(() => {
                    window.location.href = "/login";
                }, 1000);
            }
        });
    </script>
</body>

</html>