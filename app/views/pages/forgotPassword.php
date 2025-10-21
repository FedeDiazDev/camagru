<?php
require_once __DIR__ . '/../../testmail.php';
require_once __DIR__ . '/../../controllers/UserController.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {


    $userController = new UserController();
    header('Content-Type: application/json');

    $input = json_decode(file_get_contents("php://input"), true);

    if (!$input || !isset($input['action'])) {
        echo json_encode(['success' => false, 'msg' => 'Invalid request']);
        exit;
    }

    if ($input['action'] === 'recoverPassword') {
        $email = trim($input['email'] ?? '');
        if (empty($email)) {
            echo json_encode(['success' => false, 'msg' => 'Email is required']);
            exit;
        }

        $token = bin2hex(random_bytes(32));
        // $expires = date('Y-m-d H:i:s', strtotime('+1 hour'));
        $res = $userController->setPasswordResetToken($email, $token, NULL);

        if ($res) {
            if (recoverPassword($email, $token)) {
                echo json_encode(['success' => true, 'msg' => 'Password reset email sent']);
            } else {
                echo json_encode(['success' => false, 'msg' => 'Failed to send email']);
            }
        } else {
            echo json_encode(['success' => false, 'msg' => 'Email not found']);
        }
        exit;
    }

    echo json_encode(['success' => false, 'msg' => 'Unknown action']);
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
    <title>Forgot Password</title>
</head>

<body class="min-h-screen bg-gradient-to-br from-gray-900 via-purple-900 to-black">
    <header class="border-b border-gray-800 bg-black/50 backdrop-blur-xl">
        <div class="container mx-auto px-4 py-4 flex items-center justify-between">
            <a href="/" class="flex items-center gap-2">
                <i class="fa-solid fa-camera fa-fw text-purple-400 text-2xl"></i>
                <h1
                    class="text-xl lg:text-2xl font-bold bg-gradient-to-r from-purple-400 to-pink-400 bg-clip-text text-transparent">
                    Camagru
                </h1>
            </a>
            <a href="/login"
                class="flex items-center text-gray-300 hover:text-white hover:bg-gray-800 px-3 py-2 rounded-md text-sm lg:text-base">
                <i class="fa-solid fa-arrow-left w-4 h-4 mr-2"></i>
                <span class="hidden sm:inline">Back to Sign In</span>
                <span class="sm:hidden">Back</span>
            </a>
        </div>
    </header>

    <div class="flex items-center justify-center min-h-[calc(100vh-80px)] p-4">
        <div class="w-full max-w-md bg-gray-900/80 border border-gray-800 backdrop-blur-xl rounded-xl p-6">
            <div class="text-center space-y-4 hidden">
                <div class="w-16 h-16 bg-green-500/20 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fa-solid fa-circle-check text-green-400 text-2xl"></i>
                </div>
                <h2 class="text-2xl lg:text-3xl text-white mb-2">Check Your Email</h2>
                <p class="text-gray-400 text-sm lg:text-base">
                    We've sent password reset instructions to your email address
                </p>

                <div class="bg-green-500/10 border border-green-500/20 rounded-lg p-4">
                    <div class="flex items-start gap-3">
                        <i class="fa-solid fa-envelope text-green-400 mt-1 text-lg flex-shrink-0"></i>
                        <div>
                            <p class="text-green-300 font-medium text-sm">
                                Email sent successfully!
                            </p>
                            <p class="text-green-300/80 text-sm mt-1">
                                We've sent password reset instructions to
                                <strong>example@email.com</strong>
                            </p>
                        </div>
                    </div>
                </div>

                <div class="bg-gray-800/50 rounded-lg p-4 text-left">
                    <h4 class="text-white font-medium mb-2 flex items-center gap-2">
                        <i class="fa-solid fa-clock text-purple-400"></i>
                        What's next?
                    </h4>
                    <ul class="space-y-2 text-sm text-gray-300">
                        <li class="flex items-start gap-2">
                            <span class="text-purple-400 mt-1">1.</span>
                            <span>Check your email inbox (and spam folder)</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <span class="text-purple-400 mt-1">2.</span>
                            <span>Click the reset link in the email</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <span class="text-purple-400 mt-1">3.</span>
                            <span>Create a new secure password</span>
                        </li>
                    </ul>
                </div>

                <div class="pt-4 border-t border-gray-700">
                    <a href="/login"
                        class="block w-full text-center bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 text-white rounded-lg py-3">
                        Return to Sign In
                    </a>
                </div>
            </div>

            <div class="text-center space-y-4">
                <div class="w-16 h-16 bg-purple-500/20 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fa-solid fa-envelope text-purple-400 text-2xl"></i>
                </div>
                <h2 class="text-2xl lg:text-3xl text-white mb-2">Forgot Password?</h2>
                <p class="text-gray-400 text-sm lg:text-base">
                    No worries! Enter your email and we'll send you reset instructions
                </p>

                <form class="space-y-6 text-left" method="post" action="/password">
                    <div class="space-y-2">
                        <label for="email" class="text-gray-300 text-sm lg:text-base">Email Address</label>
                        <input type="email" id="email" placeholder="Enter your email address"
                            class="w-full bg-gray-800 border border-gray-700 text-white placeholder-gray-500 focus:border-purple-500 h-11 lg:h-12 rounded-lg px-3 mt-2"
                            required />
                    </div>

                    <div class="bg-purple-500/10 border border-purple-500/20 rounded-lg p-4">
                        <div class="flex items-start gap-3">
                            <i class="fa-solid fa-envelope text-purple-400 mt-1 flex-shrink-0"></i>
                            <div>
                                <p class="text-purple-300 text-sm font-medium">How it works</p>
                                <p class="text-purple-300/80 text-sm mt-1">
                                    We'll send you a secure link to reset your password. The
                                    link will expire in 1 hour for security.
                                </p>
                            </div>
                        </div>
                    </div>

                    <button type="submit"
                        class="w-full bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 text-white rounded-lg h-11 lg:h-12 disabled:opacity-50">
                        <i class="fa-solid fa-envelope mr-2"></i> Send Reset Link
                    </button>

                    <div class="text-center pt-4 border-t border-gray-700">
                        <p class="text-gray-400 text-sm mb-3">Remember your password?</p>
                        <a href="/login"
                            class="text-purple-400 hover:text-purple-300 transition-colors font-medium text-sm">
                            Back to Sign In
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
<script>
    document.addEventListener("DOMContentLoaded", () => {
        const form = document.querySelector("form");
        const emailInput = document.getElementById("email");
        const button = form.querySelector("button[type='submit']");

        form.addEventListener("submit", async (e) => {
            e.preventDefault();
            const email = emailInput.value.trim();
            if (!email) return alert("Please enter your email.");

            button.disabled = true;
            button.innerHTML = `<i class="fa-solid fa-spinner fa-spin mr-2"></i> Sending...`;

            try {
                const res = await fetch(form.action, {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json"
                    },
                    body: JSON.stringify({
                        action: "recoverPassword",
                        email
                    })
                });

                const data = await res.json();

                if (data.success) {
                    console.log("Email sent successfully! Check your inbox.");
                } else {
                    console.log((data.msg || "Something went wrong."));
                }
            } catch (err) {
                console.log("Error: " + err.message);
            }

            button.disabled = false;
            button.innerHTML = `<i class="fa-solid fa-envelope mr-2"></i> Send Reset Link`;
        });
    });
</script>

</html>