<?php
session_start();

if (!isset(($_SESSION['userId']))) {
    header("Location: /");
    die();
}

require_once __DIR__ . '/../../controllers/PostController.php';
require_once __DIR__ . '/../../controllers/CommentController.php';
require_once __DIR__ . '/../../controllers/LikeController.php';

$postControl = new PostController();
$postID = $_GET['id'];
$dataPost = json_decode($postControl->getPostById($postID));
// echo $dataPost;
// die();
$post = $dataPost->msg;

function formatTime($date)
{
    $postDate = new DateTime($date);
    $now = new DateTime();
    $diff = $now->diff($postDate);

    if ($diff->days === 0) {
        if ($diff->h > 0) {
            return "{$diff->h} hours ago";
        } elseif ($diff->i > 0) {
            return "{$diff->i} minutes ago";
        } else {
            return "a few seconds ago";
        }
    }

    if ($diff->days === 1) {
        return "Yesterday";
    }

    if ($diff->days < 7) {
        return "{$diff->days} days ago";
    }

    return $postDate->format('d M Y');
}
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST['action']) && $_POST['action'] === 'delete_post') {
        if ($post->userId !== $_SESSION['userId']) {
            echo json_encode(["success" => false, "error" => "Unauthorized"]);
            exit;
        }

        $deleted = $postControl->deletePost($postID);
        if ($deleted) {
            echo json_encode(["success" => true]);
        } else {
            echo json_encode(["success" => false, "error" => "Could not delete post"]);
        }
        exit;
    }

    if (isset($_POST['comment'])) {

        $commentController = new CommentController();
        $comment = trim(htmlspecialchars($_POST['comment']));
        $res = $commentController->addComment($postID, $comment, $_SESSION['userId']);
        if ($res) {
            echo json_encode([
                "success" => true,
                "data" => [
                    "username" => $_SESSION['username'],
                    "avatarUrl" => $_SESSION['avatarUrl'] ?? 'https://placehold.co/40x40',
                    "date" => formatTime(date('Y-m-d H:i:s')),
                    "content" => htmlspecialchars($comment)
                ]
            ]);
        } else {
            echo json_encode([
                "success" => false,
                "error" => "No se pudo guardar el comentario."
            ]);
        }
        exit;
    }
    if (isset($_POST['action']) && $_POST['action'] === 'like') {
        $likeController = new LikeController();
        $res = $likeController->like_unlike($postID, $_SESSION['userId']);
        echo json_encode([
            "success" => true,
            "likes" => $likeController->getLikesbyPost($postID),
            "liked" => $likeController->hasLiked($postID, $_SESSION['userId'])
        ]);
        exit;
    }
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
                        <img src="<?= htmlspecialchars($post->url) ?>" alt="Post image"
                            class="w-full h-full object-cover" />
                        <?php if (isset($post->userId) && $post->userId == $_SESSION['userId']): ?>
                            <form id="deletePostForm" method="POST" class="absolute top-4 right-4">
                                <input type="hidden" name="action" value="delete_post">
                                <button type="submit"
                                    class="px-3 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg text-sm font-medium transition">
                                    <i class="fa-solid fa-trash mr-1"></i> Delete
                                </button>
                            </form>
                        <?php endif; ?>
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
                                    <img src="https://placehold.co/600x600/png" alt=""
                                        class="w-12 h-12 rounded-full border-2 border-purple-500/30" />
                                    <div>
                                        <p class="font-semibold text-white"></p>
                                        <div class="flex items-center gap-2 text-gray-400 text-sm">
                                            <i class="fa-regular fa-clock text-xs"></i>
                                            <span><?= formatTime($post->date) ?></span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="flex items-center justify-between mb-4">
                                <div class="flex items-center gap-4">
                                    <?php
                                    $liked = (new LikeController())->hasLiked($postID, $_SESSION['userId']);
                                    ?>
                                    <button data-post-id="<?= $postID ?>"
                                        class="likeBtn p-2 hover:bg-gray-800 rounded-lg cursor-pointer
                                        <?= $liked ? 'text-pink-400' : 'text-gray-400' ?>">
                                        <i class="<?= $liked ? 'fa-solid fa-heart text-pink-400' : 'fa-regular fa-heart' ?> mr-2"></i>
                                        <span class="likeCount font-medium"><?= htmlspecialchars($post->likes) ?></span>
                                    </button>

                                    <button
                                        class="p-2 text-gray-400 hover:text-purple-400 hover:bg-gray-800 rounded-lg">
                                        <i class="fa-regular fa-comment mr-2"></i>
                                        <span class="font-medium"><?= count($post->comments) ?></span>
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
                                        <p class="font-semibold text-white"><?= htmlspecialchars($post->author); ?></p>
                                        <div class="flex items-center gap-2 text-gray-400 text-sm">
                                            <i class="fa-regular fa-clock text-xs"></i>
                                            <span><?= formatTime($post->date) ?></span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="flex items-center justify-between mb-6">
                                <div class="flex items-center gap-4">
                                    <button
                                        class="likeBtn p-2 text-gray-400 hover:text-pink-400 hover:bg-gray-800 rounded-lg cursor-pointer"
                                        data-post-id="<?= $postID ?>">
                                        <i class="<?= $liked ? 'fa-solid text-pink-400 fa-heart' : 'fa-regular fa-heart' ?> mr-2"></i>
                                        <span class="likeCount font-medium"><?= htmlspecialchars($post->likes) ?></span>
                                    </button>

                                    <button
                                        class="p-2 text-gray-400 hover:text-purple-400 hover:bg-gray-800 rounded-lg">
                                        <i class="fa-regular fa-comment mr-2"></i>
                                        <span class="font-medium"><?= count($post->comments) ?></span>
                                    </button>
                                </div>
                            </div>

                            <div class="space-y-4">
                                <p class="text-gray-300 leading-relaxed">
                                    Lost in the shadows of the night 🌙 This piece represents
                                    the eternal dance between light and darkness. Created with
                                    vintage film techniques and enhanced with digital magic.
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
                        <i class="fa-regular fa-comment text-purple-400"></i> Comments <?= count($post->comments) ?>
                    </h3>
                    <div class="mb-6">
                        <div class="flex gap-3">
                            <img src="https://placehold.co/600x600/png" alt=""
                                class="w-10 h-10 rounded-full border-2 border-purple-500/30" />
                            <div class="flex-1">
                                <form id="formComment" method="post" action="/post?id=<?= $postID ?>">
                                    <textarea placeholder="Add a comment..." name="comment" value="comment" required
                                        class="w-full bg-gray-800 border border-gray-700 text-white placeholder-gray-500 rounded-lg p-3 min-h-[80px]"></textarea>
                                    <div class="flex justify-end items-center mt-3">
                                        <button
                                            class="px-4 py-2 bg-gradient-to-r from-purple-600 to-pink-600 rounded-lg text-white">
                                            <i class="fa-solid fa-paper-plane mr-2"></i> Post
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="space-y-6" id="comments">
                        <?php if (!empty($post->comments)): ?>
                            <?php foreach ($post->comments as $comment): ?>
                                <div class="flex gap-3">
                                    <img src="<?= htmlspecialchars($comment->avatarUrl ?? 'https://placehold.co/40x40') ?>"
                                        alt="Avatar" class="w-10 h-10 rounded-full border-2 border-purple-500/30" />
                                    <div class="flex-1 min-w-0">
                                        <div class="bg-gray-800/50 rounded-lg p-4">
                                            <div class="flex items-center justify-between mb-2">
                                                <div class="flex items-center gap-2">
                                                    <p class="font-semibold text-white text-sm">
                                                        <?= htmlspecialchars($comment->username) ?>
                                                    </p>
                                                </div>
                                                <span class="text-gray-500 text-xs">
                                                    <?= formatTime($comment->date) ?>
                                                </span>
                                            </div>
                                            <p class="text-gray-300 leading-relaxed">
                                                <?= htmlspecialchars($comment->content) ?>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p class="text-gray-400" id="no-comments">No comments yet. Be the first!</p>
                        <?php endif; ?>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        const form = document.getElementById("formComment");
        const likeBtn = document.getElementById("likeBtn");
        const likeCountEl = document.getElementById("likeCount");
        const deleteForm = document.getElementById("deletePostForm");

        form.addEventListener("submit", async (e) => {
            e.preventDefault();
            const formData = new FormData(form);
            const response = await fetch(form.action, {
                method: 'POST',
                body: formData
            });
            const result = await response.json();
            if (result.success) {
                renderComment(result.data);
                form.reset();
            }

        });
        document.querySelectorAll(".likeBtn").forEach(btn => {
            btn.addEventListener("click", async () => {
                const postId = btn.dataset.postId;
                const formData = new FormData();
                formData.append("action", "like");

                const response = await fetch(window.location.href, {
                    method: "POST",
                    body: formData,
                    headers: {
                        "X-Requested-With": "XMLHttpRequest"
                    }
                });

                const result = await response.json();
                if (result.success) {
                    document.querySelectorAll(`.likeBtn[data-post-id="${postId}"] .likeCount`)
                        .forEach(el => el.textContent = result.likes);

                    document.querySelectorAll(`.likeBtn[data-post-id="${postId}"] i`)
                        .forEach(icon => {
                            if (result.liked) {
                                icon.classList.remove("fa-regular");
                                icon.classList.add("fa-solid", "text-pink-400");
                            } else {
                                icon.classList.add("fa-regular");
                                icon.classList.remove("fa-solid", "text-pink-400");
                            }
                        });
                }
            });
        });
        if (deleteForm) {
            deleteForm.addEventListener("submit", async (e) => {
                e.preventDefault();
                if (!confirm("Are you sure you want to delete this post?")) return;

                const formData = new FormData(deleteForm);
                const res = await fetch(window.location.href, {
                    method: "POST",
                    body: formData,
                });
                const result = await res.json();
                if (result.success) {
                    alert("Post deleted successfully.");
                    window.location.href = "/gallery";
                } else {
                    alert(result.error || "Error deleting post.");
                }
            });
        }

        function renderComment(comment) {
            const noComments = document.getElementById("no-comments");
            const commentsContainer = document.getElementById("comments");
            const div = document.createElement("div");
            if (noComments)
                noComments.style.display = 'none';
            div.classList.add("flex", "gap-3");
            div.innerHTML = `
            <img src="${comment.avatarUrl}" class="w-10 h-10 rounded-full border-2 border-purple-500/30" />
            <div class="flex-1 min-w-0">
                <div class="bg-gray-800/50 rounded-lg p-4">
                    <div class="flex items-center justify-between mb-2">
                        <div class="flex items-center gap-2">
                            <p class="font-semibold text-white text-sm">${comment.username}</p>
                        </div>
                        <span class="text-gray-500 text-xs">${comment.date}</span>
                    </div>
                    <p class="text-gray-300 leading-relaxed">${comment.content}</p>
                </div>
            </div>
        `;
            commentsContainer.prepend(div);
        }
    </script>
</body>

</html>