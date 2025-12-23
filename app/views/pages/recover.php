<?php
session_start();
require_once __DIR__ . '/../../controllers/UserController.php';

$token = $_GET['token'] ?? null;

if (!$token) {
    die('Invalid link');
}
// $userController = new UserController();
// $userRes = json_decode($userController->getUserByToken($token));
// $user = $userRes->msg;
// echo $user->id;
// exit;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $userController = new UserController();
    $userRes = json_decode($userController->getUserByResetToken($token));
    $user = $userRes->msg;
    if (!$user) {
        die('Invalid or expired token');
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $password = $_POST['password'];
        $confirm = $_POST['confirm'];
        if ($password === $confirm) {
            $userController->updatePassword($user->id, $password);
            echo "Password updated successfully!";
        } else {
            echo "Passwords don't match.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Change Password</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.0/css/all.min.css"
        integrity="sha512-DxV+EoADOkOygM4IR9yXP8Sb2qwgidEmeqAEmDKIOfPRQZOWbXCzLC6vjbZyy0vPisbH2SyW27+ddLVCN+OMzQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body class="min-h-screen bg-gradient-to-br from-gray-900 via-purple-900 to-black">
    <?php include __DIR__ . '/../templates/main_header.php'; ?>
    <main class="flex items-center justify-center min-h-[calc(100vh-80px)] p-4">
        <div class="w-full max-w-md bg-gray-900/80 border border-gray-800 rounded-2xl backdrop-blur-xl p-6">
            <div class="text-center mb-6">
                <div class="w-16 h-16 bg-purple-500/20 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fa-solid fa-lock text-purple-400 text-2xl"></i>
                </div>
                <h2 class="text-2xl lg:text-3xl text-white mb-2">Change Password</h2>
                <p class="text-gray-400 text-sm lg:text-base">Create a new secure password for your account</p>
            </div>

            <form id="changePasswordForm" class="space-y-6" method="POST">
                <div>
                    <label for="password" class="text-gray-300 text-sm">New Password</label>
                    <div class="relative mt-2">
                        <input id="password" type="password" placeholder="Enter your new password"
                            class="w-full bg-gray-800 border border-gray-700 text-white rounded-lg px-4 py-3 focus:border-purple-500 placeholder-gray-500 pr-12">
                        <button type="button" id="togglePassword"
                            class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-white">
                            <i class="fa-solid fa-eye"></i>
                        </button>
                    </div>

                    <div id="strengthContainer" class="hidden mt-3">
                        <div class="flex justify-between text-xs text-gray-400">
                            <span>Password Strength:</span>
                            <span id="strengthText">Very Weak</span>
                        </div>
                        <div class="w-full bg-gray-700 rounded-full h-2 mt-1">
                            <div id="strengthBar" class="h-2 rounded-full bg-red-500 transition-all"></div>
                        </div>
                    </div>
                </div>

                <div>
                    <label for="confirmPassword" class="text-gray-300 text-sm">Confirm New Password</label>
                    <div class="relative mt-2">
                        <input id="confirmPassword" type="password" placeholder="Confirm your new password"
                            class="w-full bg-gray-800 border border-gray-700 text-white rounded-lg px-4 py-3 focus:border-purple-500 placeholder-gray-500 pr-12">
                        <button type="button" id="toggleConfirm"
                            class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-white">
                            <i class="fa-solid fa-eye"></i>
                        </button>
                    </div>
                    <div id="matchMessage" class="flex items-center gap-2 mt-2 text-xs"></div>
                </div>

                <div id="errorBox" class="hidden bg-red-500/10 border border-red-500/20 rounded-lg p-4">
                    <div class="flex items-center gap-2">
                        <i class="fa-solid fa-circle-exclamation text-red-400"></i>
                        <p id="errorMessage" class="text-red-300 text-sm"></p>
                    </div>
                </div>

                <div class="bg-purple-500/10 border border-purple-500/20 rounded-lg p-4">
                    <p class="text-purple-300 text-sm font-medium mb-2">Password Requirements:</p>
                    <ul class="text-xs text-purple-300/80 space-y-1">
                        <li><i class="fa-solid fa-circle text-[6px] mr-2"></i>At least 8 characters</li>
                        <li><i class="fa-solid fa-circle text-[6px] mr-2"></i>Lowercase letter</li>
                        <li><i class="fa-solid fa-circle text-[6px] mr-2"></i>Uppercase letter</li>
                        <li><i class="fa-solid fa-circle text-[6px] mr-2"></i>Number</li>
                        <li><i class="fa-solid fa-circle text-[6px] mr-2"></i>Special character (!@#$%^&*)</li>
                    </ul>
                </div>

                <button type="submit"
                    class="w-full bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 text-white rounded-lg py-3 font-medium disabled:opacity-50 transition">
                    <i class="fa-solid fa-lock mr-2"></i>Change Password
                </button>

                <div class="text-center pt-4 border-t border-gray-700">
                    <p class="text-gray-400 text-sm mb-3">Changed your mind?</p>
                    <a href="/profile" class="text-purple-400 hover:text-purple-300 transition-colors text-sm font-medium">
                        Cancel and go back
                    </a>
                </div>
            </form>

            <div class="mt-6 pt-6 border-t border-gray-700">
                <div class="flex items-start gap-3 bg-gray-800/30 rounded-lg p-4">
                    <i class="fa-solid fa-shield-halved text-purple-400 mt-0.5"></i>
                    <p class="text-gray-300 text-xs leading-relaxed">
                        For your security, make sure to use a strong password that you don't use on other websites.
                    </p>
                </div>
            </div>
        </div>
    </main>
    <?php include __DIR__ . '/../templates/footer.php'; ?>
    <script src="/js/alert.js"></script>
    <script>
        const passwordInput = document.getElementById("password");
        const confirmInput = document.getElementById("confirmPassword");
        const togglePassword = document.getElementById("togglePassword");
        const toggleConfirm = document.getElementById("toggleConfirm");
        const strengthBar = document.getElementById("strengthBar");
        const strengthText = document.getElementById("strengthText");
        const strengthContainer = document.getElementById("strengthContainer");
        const matchMessage = document.getElementById("matchMessage");
        const errorBox = document.getElementById("errorBox");
        const errorMessage = document.getElementById("errorMessage");

        const checkStrength = (pwd) => {
            let strength = 0;
            if (pwd.length >= 8) strength++;
            if (/[a-z]/.test(pwd)) strength++;
            if (/[A-Z]/.test(pwd)) strength++;
            if (/[0-9]/.test(pwd)) strength++;
            if (/[^A-Za-z0-9]/.test(pwd)) strength++;
            return strength;
        };

        passwordInput.addEventListener("input", (e) => {
            const pwd = e.target.value;
            const strength = checkStrength(pwd);
            strengthContainer.classList.toggle("hidden", !pwd);

            const colors = ["bg-red-500", "bg-orange-500", "bg-yellow-500", "bg-blue-500", "bg-green-500"];
            const texts = ["Very Weak", "Weak", "Fair", "Good", "Strong"];

            strengthBar.className = `h-2 rounded-full transition-all duration-300 ${colors[strength - 1] || "bg-red-500"}`;
            strengthBar.style.width = `${(strength / 5) * 100}%`;
            strengthText.textContent = texts[strength - 1] || "Very Weak";
        });

        confirmInput.addEventListener("input", () => {
            matchMessage.innerHTML = "";
            if (!confirmInput.value) return;
            if (passwordInput.value === confirmInput.value) {
                matchMessage.innerHTML = `<i class="fa-solid fa-circle-check text-green-400"></i><span class="text-green-400">Passwords match</span>`;
            } else {
                matchMessage.innerHTML = `<i class="fa-solid fa-circle-exclamation text-red-400"></i><span class="text-red-400">Passwords don't match</span>`;
            }
        });

        togglePassword.addEventListener("click", () => {
            const type = passwordInput.type === "password" ? "text" : "password";
            passwordInput.type = type;
            togglePassword.innerHTML = `<i class="fa-solid fa-${type === "password" ? "eye" : "eye-slash"}"></i>`;
        });

        toggleConfirm.addEventListener("click", () => {
            const type = confirmInput.type === "password" ? "text" : "password";
            confirmInput.type = type;
            toggleConfirm.innerHTML = `<i class="fa-solid fa-${type === "password" ? "eye" : "eye-slash"}"></i>`;
        });
        const form = document.getElementById("changePasswordForm");
        form.addEventListener("submit", async (e) => {
            e.preventDefault();

            const pwd = passwordInput.value;
            const confirm = confirmInput.value;
            const strength = checkStrength(pwd);

            if (!pwd || !confirm) {
                errorBox.classList.remove("hidden");
                errorMessage.textContent = "Please fill in both fields";
                return;
            }

            if (pwd !== confirm) {
                errorBox.classList.remove("hidden");
                errorMessage.textContent = "Passwords don't match";
                return;
            }

            if (strength < 3) {
                errorBox.classList.remove("hidden");
                errorMessage.textContent = "Password is too weak. Please choose a stronger one.";
                return;
            }

            errorBox.classList.add("hidden");
            try {
                const response = await fetch(form.action, {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/x-www-form-urlencoded"
                    },
                    body: new URLSearchParams({
                        password: pwd,
                        confirm: confirm
                    })
                });

                const text = await response.text();

                if (text.includes("successfully")) {
                    showAlert("Password changed successfully!", "success");
                    setTimeout(() => {
                        window.location.href = "/login";
                    })
                } else {
                    showAlert(text, "error");
                }
            } catch (err) {
                // console.error(err);
                showAlert("Error changing password. Please try again later.", "error");
            }
        });
    </script>
</body>

</html>