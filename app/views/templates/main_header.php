<header class="w-full bg-black/50 backdrop-blur-sm border-b border-gray-800">
    <div class="max-w-7xl mx-auto p-4 flex items-center justify-between">

        <div class="flex items-center gap-3">
            <div class="relative">
                <i class="fas fa-camera w-10 h-10 text-purple-400"></i>
                <div class="absolute -top-1 -right-1 w-4 h-4 bg-purple-500 rounded-full animate-pulse"></div>
            </div>
            <div>
                <h1 class="text-xl md:text-2xl font-bold bg-gradient-to-r from-purple-400 to-pink-400 bg-clip-text text-transparent">Camagru</h1>
                <p class="text-xs text-gray-400 hidden sm:block">Dark Photography</p>
            </div>
        </div>
        <button id="menuBtn" class="md:hidden text-gray-300 text-2xl">
            <i class="fas fa-bars"></i>
        </button>
    <div class="hidden md:flex items-center gap-10">

            <nav class="flex gap-8 text-sm xl:text-lg">
                <?php if (!isset($_SESSION['userId'])): ?>
                    <a href="/" class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-400 hover:text-white hover:bg-gray-800/50 transition-all">
                        <i class="fa-solid fa-star w-5 h-5"></i>
                        <span class="font-medium">Discover</span>
                    </a>
                <?php endif; ?>
                <a href="/camera" class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-400 hover:text-white hover:bg-gray-800/50 transition-all">
                    <i class="fas fa-camera w-5 h-5"></i>
                    <span>Create</span>
                </a>
                <a href="/gallery" class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-400 hover:text-white hover:bg-gray-800/50 transition-all">
                    <i class="fas fa-users w-5 h-5"></i>
                    <span>Gallery</span>
                </a>
                <a href="/profile" class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-400 hover:text-white hover:bg-gray-800/50 transition-all">
                    <i class="fas fa-chart-line w-5 h-5"></i>
                    <span>Profile</span>
                </a>
            </nav>
        </div>
        <div class="flex gap-4">
            <?php if (isset($_SESSION['userId'])): ?>
                <a href="/logout" class="w-[120px] text-center border border-gray-600 text-gray-300 hover:bg-gray-800 hover:text-white rounded py-2">
                    Logout
                </a>
            <?php else: ?>
                <a href="/login" class="px-6 py-2 border border-gray-600 text-gray-300 hover:bg-gray-800 hover:text-white rounded transition">
                    Sign In
                </a>
                <a href="/register" class="px-6 py-2 bg-gradient-to-r from-purple-600 to-pink-600 text-white rounded hover:from-purple-700 hover:to-pink-700 transition">
                    Sign Up
                </a>
            <?php endif; ?>
        </div>
    </div>

    <div id="mobileMenu" class="hidden flex flex-col gap-4 bg-black/70 p-6 border-t border-gray-800 md:hidden">

        <nav class="flex flex-col gap-3">
            <?php if (!isset($_SESSION['userId'])): ?>
                <a href="/" class="px-4 py-3 rounded-xl bg-purple-600/20 text-purple-300 border border-purple-500/30">
                    Discover
                </a>
            <?php endif; ?>
            <a href="/camera" class="px-4 py-3 rounded-xl text-gray-300 hover:bg-gray-800/50">
                Create
            </a>
            <a href="/gallery" class="px-4 py-3 rounded-xl text-gray-300 hover:bg-gray-800/50">
                Gallery
            </a>
            <a href="/profile" class="px-4 py-3 rounded-xl text-gray-300 hover:bg-gray-800/50">
                Profile
            </a>
        </nav>

        <div class="flex flex-col gap-3 mt-4">
            <?php if (isset($_SESSION['userId'])): ?>
                <a href="/logout" class="w-full text-center border border-gray-600 text-gray-300 hover:bg-gray-800 hover:text-white rounded py-2">
                    Logout
                </a>
            <?php else: ?>
                <a href="/register" class="w-full text-center bg-gradient-to-r from-purple-600 to-pink-600 text-white rounded py-2">
                    Sign Up
                </a>
                <a href="/login" class="w-full text-center border border-gray-600 text-gray-300 hover:bg-gray-800 hover:text-white rounded py-2">
                    Sign In
                </a>
            <?php endif; ?>
        </div>
    </div>
</header>

<script>
    const btn = document.getElementById("menuBtn");
    const menu = document.getElementById("mobileMenu");
    btn.addEventListener("click", () => {
        menu.classList.toggle("hidden");
    });
</script>