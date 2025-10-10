<!-- checkear verificacion en verify.php y poner a 1 la flag de email verificacion-->
<!DOCTYPE html>
<html lang="en" class="bg-gradient-to-br from-gray-900 via-purple-900 to-black min-h-screen">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Email Verification | Camagru</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://kit.fontawesome.com/a2e0f5d2f3.js" crossorigin="anonymous"></script>
  </head>
  <body class="text-gray-300">    
    <header class="border-b border-gray-800 bg-black/50 backdrop-blur-xl">
      <div class="container mx-auto px-4 py-4 flex items-center justify-between">
        <a href="/" class="flex items-center gap-2">
          <i class="fa-solid fa-camera text-purple-400 text-xl lg:text-2xl"></i>
          <h1
            class="text-xl lg:text-2xl font-bold bg-gradient-to-r from-purple-400 to-pink-400 bg-clip-text text-transparent"
          >
            Camagru
          </h1>
        </a>
        <a
          href="/"
          class="text-gray-300 hover:text-white hover:bg-gray-800 text-sm lg:text-base px-3 py-2 rounded-md transition"
        >
          <i class="fa-solid fa-arrow-left mr-2"></i>
          <span class="hidden sm:inline">Back to Home</span>
          <span class="sm:hidden">Back</span>
        </a>
      </div>
    </header>
    
    <main class="flex items-center justify-center min-h-[calc(100vh-80px)] p-4">
      <div class="w-full max-w-md bg-gray-900/80 border border-gray-800 backdrop-blur-xl rounded-2xl shadow-xl p-6 text-center">        
        <div id="icon" class="w-16 h-16 mx-auto mb-4 bg-purple-500/20 rounded-full flex items-center justify-center">
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
          <div class="bg-green-500/10 border border-green-500/20 rounded-lg p-4 text-left">
            <div class="flex items-start gap-3">
              <i class="fa-solid fa-circle-check text-green-400 mt-1"></i>
              <div>
                <p class="text-green-300 font-medium text-sm">Successfully verified!</p>
                <p class="text-green-300/80 text-sm mt-1">
                  Your email has been verified and your account is now active.
                </p>
              </div>
            </div>
          </div>

          <div class="bg-gray-800/50 rounded-lg p-4 text-left">
            <h4 class="text-white font-medium mb-3 flex items-center gap-2">
              <i class="fa-solid fa-shield-halved text-purple-400"></i>
              What's next?
            </h4>
            <ul class="space-y-2 text-sm text-gray-300">
              <li class="flex items-start gap-2">
                <span class="text-purple-400 mt-1">✓</span>
                <span>Your account is now fully activated</span>
              </li>
              <li class="flex items-start gap-2">
                <span class="text-purple-400 mt-1">✓</span>
                <span>You can now access all features</span>
              </li>
              <li class="flex items-start gap-2">
                <span class="text-purple-400 mt-1">✓</span>
                <span>Start creating and sharing your dark art</span>
              </li>
            </ul>
          </div>

          <div class="space-y-3">
            <a
              href="/login"
              class="block w-full bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 text-white text-center py-3 rounded-md font-medium transition"
              >Sign In to Your Account</a
            >
            <a
              href="/"
              class="block w-full border border-gray-600 text-gray-300 hover:bg-gray-800 hover:text-white py-3 rounded-md text-center font-medium transition"
              >Explore the Gallery</a
            >
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
    
    <script>
      setTimeout(() => {
        document.getElementById("loading").classList.add("hidden");
        document.getElementById("icon").innerHTML =
          '<i class="fa-solid fa-circle-check text-green-400 text-2xl animate-bounce"></i>';
        document.getElementById("icon").className =
          "w-16 h-16 mx-auto mb-4 bg-green-500/20 rounded-full flex items-center justify-center";
        document.getElementById("title").innerText = "Email Verified!";
        document.getElementById("subtitle").innerText =
          "Your email has been successfully verified.";
        document.getElementById("success").classList.remove("hidden");
      }, 3000);
    </script>
  </body>
</html>
