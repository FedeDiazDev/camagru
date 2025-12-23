<?php
session_start();
require_once __DIR__ . '/../../controllers/UserController.php';

$controller = new UserController();

$token = $_GET['token'] ?? null;

if ($token) {
	$result = $controller->verifyEmail($token);
} else {
	$result = ['res' => false, 'msg' => 'Token not provided'];
}
$jsonResult = json_encode($result);
?>

<!DOCTYPE html>
<html lang="en" class="bg-gradient-to-br from-gray-900 via-purple-900 to-black min-h-screen">

<head>
	<meta charset="UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<title>Email Verification | Camagru</title>
	<script src="https://cdn.tailwindcss.com"></script>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.0/css/all.min.css"
		integrity="sha512-DxV+EoADOkOygM4IR9yXP8Sb2qwgidEmeqAEmDKIOfPRQZOWbXCzLC6vjbZyy0vPisbH2SyW27+ddLVCN+OMzQ=="
		crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body class="text-gray-300">
	<?php include __DIR__ . '/../templates/main_header.php'; ?>
	<main class="flex items-center justify-center min-h-[calc(100vh-80px)] p-4">
		<div
			class="w-full max-w-md bg-gray-900/80 border border-gray-800 backdrop-blur-xl rounded-2xl shadow-xl p-6 text-center">
			<div id="icon"
				class="w-16 h-16 mx-auto mb-4 bg-purple-500/20 rounded-full flex items-center justify-center">
				<i class="fa-solid fa-spinner text-purple-400 text-2xl animate-spin"></i>
			</div>

			<h2 id="title" class="text-2xl lg:text-3xl text-white mb-2">Verifying Email...</h2>
			<p id="subtitle" class="text-gray-400 text-sm lg:text-base">
				Please wait while we verify your email address
			</p>

			<div id="loading" class="flex justify-center py-6 space-x-2">
				<div class="w-3 h-3 bg-purple-400 rounded-full animate-bounce"></div>
				<div class="w-3 h-3 bg-pink-400 rounded-full animate-bounce delay-100"></div>
				<div class="w-3 h-3 bg-purple-400 rounded-full animate-bounce delay-200"></div>
			</div>

			<div id="success" class="hidden space-y-6">
				<div id="messageContainer"></div>

				<div class="space-y-3">
					<a href="/login"
						class="block w-full bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 text-white text-center py-3 rounded-md font-medium transition">Sign
						In to Your Account</a>
					<a href="/gallery"
						class="block w-full border border-gray-600 text-gray-300 hover:bg-gray-800 hover:text-white py-3 rounded-md text-center font-medium transition">Explore
						the Gallery</a>
				</div>
			</div>

			<div class="mt-8 pt-6 border-t border-gray-700 text-center text-xs text-gray-500">
				<p class="mb-2">Need help with verification?</p>
				<div class="flex justify-center gap-4">
					<a href="/help" class="text-gray-400 hover:text-white transition-colors">Help Center</a>
					<span class="text-gray-600">•</span>
					<a href="/contact" class="text-gray-400 hover:text-white transition-colors">Contact Support</a>
				</div>
			</div>
		</div>
	</main>
	<?php include __DIR__ . '/../templates/footer.php'; ?>

	<script>
		const result = JSON.parse(<?php echo $jsonResult; ?>);
		// console.log(typeof(result));
		// console.log(result);
		setTimeout(() => {
			document.getElementById("loading").classList.add("hidden");
			const icon = document.getElementById("icon");
			const title = document.getElementById("title");
			const subtitle = document.getElementById("subtitle");
			const success = document.getElementById("success");
			const messageContainer = document.getElementById("messageContainer");

			if (result.res) {
				icon.innerHTML = '<i class="fa-solid fa-circle-check text-green-400 text-2xl animate-bounce"></i>';
				icon.className = "w-16 h-16 mx-auto mb-4 bg-green-500/20 rounded-full flex items-center justify-center";
				title.innerText = "Email Verified!";
				subtitle.innerText = "Your email has been successfully verified.";
				messageContainer.innerHTML = `<div class="bg-green-500/10 border border-green-500/20 rounded-lg p-4 text-left mt-4">
			<div class="flex items-start gap-3">
				<i class="fa-solid fa-circle-check text-green-400 mt-1"></i>
				<div>
					<p class="text-green-300 font-medium text-sm">${result.msg}</p>
				</div>
			</div>
		</div>`;
			} else {
				icon.innerHTML = '<i class="fa-solid fa-circle-xmark text-red-400 text-2xl animate-bounce"></i>';
				icon.className = "w-16 h-16 mx-auto mb-4 bg-red-500/20 rounded-full flex items-center justify-center";
				title.innerText = "Verification Failed";
				subtitle.innerText = "There was a problem verifying your email.";
				messageContainer.innerHTML = `<div class="bg-red-500/10 border border-red-500/20 rounded-lg p-4 text-left">
			<div class="flex items-start gap-3">
				<i class="fa-solid fa-circle-xmark text-red-400 mt-1"></i>
				<div>
					<p class="text-red-300 font-medium text-sm">${result.msg}</p>
				</div>
			</div>
		</div>`;
			}

			success.classList.remove("hidden");
		}, 1000);
	</script>
</body>

</html>