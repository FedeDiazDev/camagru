<?php
session_start();

if (!isset(($_SESSION['userId']))) {
    header("Location: /login");
    die();
}
require_once __DIR__ . '/../../controllers/UserController.php';
$id = $_SESSION['userId'];
if ($_SERVER["REQUEST_METHOD"] === "GET")
    if ($userCon = new UserController())
        $user = json_decode($userCon->getUserById($id), true);
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $data = new stdClass();
    $data->id = $id;
    $data->username = trim(htmlspecialchars($_POST['username']));
    $data->email = trim(htmlspecialchars($_POST['email']));
    $data->currentPassword = htmlspecialchars($_POST['currentPassword']);
    $data->password = htmlspecialchars($_POST['password']);
    $data->confirmPassword = htmlspecialchars($_POST['confirmPassword']);
    $data->notifications = $_POST['notifications'] ?? 0;
    $userController = new UserController();
    $res = $userController->updateUser(json_encode($data));
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
    <title>Profile</title>
</head>

<body class="min-h-screen bg-gradient-to-br from-gray-900 via-purple-900 to-black">

    <?php include __DIR__ . '/../templates/main_header.php'; ?>
    <div class="flex items-center justify-center min-h-[calc(100vh-80px)] p-4">
        <div class="w-full max-w-2xl bg-gray-900/80 border border-gray-800 backdrop-blur-xl rounded-2xl shadow-lg p-6">
            <h2 class="text-2xl lg:text-3xl text-white text-center mb-2">Update Profile</h2>
            <p class="text-gray-400 text-center mb-6">Manage your account information and security settings</p>

            <form id="profileForm" class="space-y-6" action="/profile" method="post">
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-white flex items-center gap-2">
                            <i class="fas fa-user text-purple-400"></i> Account Information
                        </h3>
                        <button type="button" id="editBtn"
                            class="px-3 py-1 rounded-lg border border-purple-600 text-purple-400 hover:bg-purple-600 hover:text-white text-sm flex items-center">
                            <i class="fas fa-edit mr-2"></i> Edit
                        </button>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="username" class="block text-sm text-gray-300 mb-1">Username</label>
                            <input id="username" type="text" name="username" value=<?= htmlspecialchars($user['msg']['username']) ?> disabled
                                class="w-full h-11 bg-gray-900/50 border border-gray-700 rounded-lg px-3 text-gray-400 placeholder:text-gray-500 focus:border-purple-500 cursor-not-allowed" />
                        </div>

                        <div>
                            <label for="email" class="block text-sm text-gray-300 mb-1">Email Address</label>
                            <input id="email" type="email" name="email" value=<?= htmlspecialchars($user['msg']['email']) ?> disabled
                                class="w-full h-11 bg-gray-900/50 border border-gray-700 rounded-lg px-3 text-gray-400 placeholder:text-gray-500 focus:border-purple-500 cursor-not-allowed" />
                        </div>
                    </div>
                </div>

                <div class="space-y-4 border-t border-gray-700 pt-6 hidden" id="pass-container">
                    <h3 class="text-lg font-semibold text-white flex items-center gap-2">
                        <i class="fas fa-eye text-purple-400"></i> Change Password
                        <span class="text-sm font-normal text-gray-400">(Optional)</span>
                    </h3>

                    <div class="space-y-4">
                        <div>
                            <label for="currentPassword" class="block text-sm text-gray-300 mb-1">Current
                                Password</label>
                            <div class="relative">
                                <input id="currentPassword" type="password" name="currentPassword"
                                    placeholder="Enter current password" disabled
                                    class="w-full h-11 bg-gray-900/50 border border-gray-700 rounded-lg px-3 text-gray-400 placeholder:text-gray-500 focus:border-purple-500 pr-10 cursor-not-allowed" />
                                <button type="button" onclick="togglePassword('currentPassword','iconCurrent')"
                                    class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-white">
                                    <i id="iconCurrent" class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="newPassword" class="block text-sm text-gray-300 mb-1">New Password</label>
                                <div class="relative">
                                    <input id="newPassword" type="password" name="password"
                                        placeholder="Enter new password" disabled
                                        class="w-full h-11 bg-gray-900/50 border border-gray-700 rounded-lg px-3 text-gray-400 placeholder:text-gray-500 focus:border-purple-500 pr-10 cursor-not-allowed" />
                                    <button type="button" onclick="togglePassword('newPassword','iconNew')"
                                        class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-white">
                                        <i id="iconNew" class="fas fa-eye"></i>
                                    </button>
                                </div>
                            </div>

                            <div>
                                <label for="confirmPassword" class="block text-sm text-gray-300 mb-1">Confirm New
                                    Password</label>
                                <div class="relative">
                                    <input id="confirmPassword" name="confirmPassword" type="password"
                                        placeholder="Confirm new password" disabled
                                        class="w-full h-11 bg-gray-900/50 border border-gray-700 rounded-lg px-3 text-gray-400 placeholder:text-gray-500 focus:border-purple-500 pr-10 cursor-not-allowed" />
                                    <button type="button" onclick="togglePassword('confirmPassword','iconConfirm')"
                                        class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-white">
                                        <i id="iconConfirm" class="fas fa-eye"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="bg-purple-900/20 border border-purple-500/30 rounded-lg p-4">
                            <p class="text-purple-300 text-sm font-bold">Password Requirements:</p>
                            <ul class="text-purple-300/80 text-sm mt-2 space-y-1">
                                <li>• At least 8 characters long</li>
                                <li>• Include uppercase and lowercase letters</li>
                                <li>• Include at least one number</li>
                                <li>• Include at least one special character</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="flex flex-col sm:flex-row gap-3 ">
                    <button type="submit" id="saveBtn"
                        class="flex-1 h-12 rounded-lg bg-gradient-to-r hidden from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 text-white flex items-center justify-center">
                        <i class="fas fa-save mr-2"></i> Save Changes
                    </button>
                    <button type="button" id="cancelBtn"
                        class="flex-1 h-12 rounded-lg border hidden border-gray-600 text-gray-300 hover:bg-gray-800 hover:text-white flex items-center justify-center">
                        <i class="fas fa-times mr-2"></i> Cancel
                    </button>
                </div>
                <div class="col-span-2">
                    <label for="notifications" class="flex items-center gap-3 text-sm text-gray-300">
                        <input id="notifications" type="checkbox" name="notifications" value="1" <?= ($user['msg']['emailPreference'] == 1) ? 'checked' : '' ?> disabled
                            class="w-5 h-5 text-purple-600 bg-gray-900/50 border-gray-700 rounded focus:ring-purple-500 cursor-not-allowed">
                        <span>Quiero recibir notificaciones por correo electrónico</span>
                    </label>
                </div>

            </form>
            <div class="mt-8 pt-6 border-t border-gray-700">
                <h3 class="text-lg font-semibold text-white mb-4">Account Actions</h3>
                <div class="grid grid-cols-1 gap-3">
                    <a href="/gallery"
                        class="h-11 rounded-lg border border-gray-600 text-gray-300 hover:bg-gray-800 hover:text-white flex items-center justify-center">
                        View My Photos
                    </a>
                </div>
            </div>
        </div>

    </div>
    <?php include __DIR__ . '/../templates/footer.php'; ?>

    <script>
        function togglePassword(id, iconId) {
            const input = document.getElementById(id);
            const icon = document.getElementById(iconId);
            if (input.type === "password") {
                input.type = "text";
                icon.classList.replace("fa-eye", "fa-eye-slash");
            } else {
                input.type = "password";
                icon.classList.replace("fa-eye-slash", "fa-eye");
            }
        }

        const editBtn = document.getElementById("editBtn");
        const saveBtn = document.getElementById("saveBtn");
        const cancelBtn = document.getElementById("cancelBtn");
        const passContainer = document.getElementById("pass-container");
        const form = document.getElementById("profileForm");
        const inputs = form.querySelectorAll("input");

        editBtn.addEventListener("click", () => {
            inputs.forEach(input => {
                input.removeAttribute("disabled");
                cancelBtn.classList.remove("hidden");
                saveBtn.classList.remove("hidden");
                passContainer.classList.remove("hidden");
                input.classList.remove("cursor-not-allowed", "text-gray-400", "bg-gray-900/50");
                input.classList.add("bg-gray-800", "text-white");
            });
        });

        cancelBtn.addEventListener("click", () => {
            inputs.forEach(input => {
                input.setAttribute("disabled", true);
                cancelBtn.classList.add("hidden");
                saveBtn.classList.add("hidden");
                passContainer.classList.add("hidden");
                input.classList.add("cursor-not-allowed", "text-gray-400", "bg-gray-900/50");
                input.classList.remove("bg-gray-800", "text-white");
            });
        });
        form.addEventListener("submit", async (e) => {
            e.preventDefault();
            const formData = new FormData(form);
            const response = await fetch(form.action, {
                method: 'POST',
                body: formData
            });
            const result = await response.json();
            console.log(result);
            if (!result.res) { //TODO:: pintar eerror en fron
                console.log(result.msg);
            } else {
                window.location.href = "/profile";
            }
        })
    </script>

</body>

</html>