<?php
require_once __DIR__ . '/../../config/php/database.php';
require_once __DIR__ . '/../models/Post.php';
require_once __DIR__ . '/./UserController.php';

class PostController
{
    private $post;

    public function __construct()
    {
        $database = new Database();
        $this->post = new Post($database->connect());
    }
    public function getPostById($id)
    {
        if (!$id) {
            return json_encode([
                'res' => false,
                'msg' => "Missing id"
            ]);
        }
        $post = $this->post->getPostById($id);
        if (!$post) {
            return json_encode([
                'res' => false,
                'msg' => "Couldn't get the post"
            ]);
        }
        return json_encode([
            'res' => true,
            'msg' => [
                'id' => $post->id,
                'title' => $post->title,
                'date' => $post->date,
                'url' => $post->mediaUrl,
                'author' => $post->author,
                'likes' => $post->likes,
                'comments' => $post->comments,
                'userId' => $post->userId
            ]
        ]);
    }

    public function getPosts($limit, $offset)
    {
        if ($limit <= 0 || $offset < 0) {
            return json_encode([
                'res' => false,
                'msg' => "Error on pagination"
            ]);
        }

        $posts = $this->post->getPosts($limit, $offset);

        if ($posts === false) {
            return json_encode([
                'res' => false,
                'msg' => "Database error"
            ]);
        }

        if (empty($posts)) {
            return json_encode([
                'res' => true,
                'msg' => [],
                'info' => "No posts found"
            ]);
        }

        return json_encode([
            'res' => true,
            'msg' => $posts
        ]);
    }


    public function getUserPosts($userId, $limit, $offset)
    {
        if (!$userId) {
            return json_encode([
                'res' => false,
                'msg' => "Missing user id"
            ]);
        }
        $userController = new UserController();
        if (!$userController->getUserById($userId)) {
            return json_encode([
                'res' => false,
                'msg' => "User doesn't exists"
            ]);
        }
        $posts = $this->post->getUserPosts($userId, $limit, $offset);
        if (!$posts) {
            return json_encode([
                'res' => false,
                'msg' => "Error getting posts"
            ]);
        }
        return json_encode([
            'res' => true,
            'msg' => $posts
        ]);
    }

    public function createPost($userId, $title, $baseImage, $stickers = [], $filter = 'none', $brightness = 100, $contrast = 100)
    {
        if (!$userId || empty($title) || !$baseImage) {
            return "Error: missing data";
        }

        $dir = __DIR__ . '/../../public/images/posts/';
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }

        $imageData = explode(',', $baseImage);
        if (count($imageData) !== 2) {
            return "Error: invalid base64 data";
        }

        $decodedBase = base64_decode(str_replace(' ', '+', $imageData[1]));
        if ($decodedBase === false) {
            return "Error: base64 decode failed";
        }

        $baseImg = imagecreatefromstring($decodedBase);
        if (!$baseImg) {
            return "Error: could not create image from base64";
        }

        imagesavealpha($baseImg, true);
        imagealphablending($baseImg, true);

        foreach ($stickers as $sticker) {
            $stickerPath = __DIR__ . '/../../public/images/' . basename($sticker['src']);
            if (!file_exists($stickerPath)) {
                file_put_contents('/tmp/camagru_stickers.log', "No existe: $stickerPath\n", FILE_APPEND);
                continue;
            }

            $stickerImg = imagecreatefrompng($stickerPath);
            if (!$stickerImg) continue;

            $width = $sticker['width'] ?? imagesx($stickerImg);
            $height = $sticker['height'] ?? imagesy($stickerImg);

            $resized = imagecreatetruecolor($width, $height);
            imagesavealpha($resized, true);
            imagealphablending($resized, false);
            $transparent = imagecolorallocatealpha($resized, 0, 0, 0, 127);
            imagefill($resized, 0, 0, $transparent);

            imagecopyresampled($resized, $stickerImg, 0, 0, 0, 0, $width, $height, imagesx($stickerImg), imagesy($stickerImg));

            $x = $sticker['x'] ?? 0;
            $y = $sticker['y'] ?? 0;

            imagecopy($baseImg, $resized, $x, $y, 0, 0, $width, $height);

            imagedestroy($resized);
            imagedestroy($stickerImg);
        }

        imagefilter($baseImg, IMG_FILTER_BRIGHTNESS, $brightness - 100);
        imagefilter($baseImg, IMG_FILTER_CONTRAST, 100 - $contrast);

        switch ($filter) {
            case 'none':
                break;

            case 'noir':
                imagefilter($baseImg, IMG_FILTER_GRAYSCALE);
                imagefilter($baseImg, IMG_FILTER_CONTRAST, -50);
                break;

            case 'gothic':
                imagefilter($baseImg, IMG_FILTER_GRAYSCALE);
                imagefilter($baseImg, IMG_FILTER_COLORIZE, 30, 30, 60, 30);
                imagefilter($baseImg, IMG_FILTER_CONTRAST, -20);
                break;

            case 'shadow':
                imagefilter($baseImg, IMG_FILTER_CONTRAST, -40);
                imagefilter($baseImg, IMG_FILTER_BRIGHTNESS, -20);
                imagefilter($baseImg, IMG_FILTER_COLORIZE, 20, 20, 20, 50);
                break;

            case 'mystic':
                imagefilter($baseImg, IMG_FILTER_GRAYSCALE);
                imagefilter($baseImg, IMG_FILTER_COLORIZE, 120, 0, 120, 40);
                imagefilter($baseImg, IMG_FILTER_BRIGHTNESS, 15);
                break;
        }


        $filename = 'post_' . time() . '_' . $userId . '.png';
        $filePath = $dir . $filename;
        imagealphablending($baseImg, false);
        imagesavealpha($baseImg, true);

        if (!imagepng($baseImg, $filePath)) {
            imagedestroy($baseImg);
            return "Error: could not save image";
        }

        imagedestroy($baseImg);

        $relativePath = '/images/posts/' . $filename;

        $postId = $this->post->createPost($userId, $title, $relativePath);
        if (!$postId) {
            return "Error: could not save post in database";
        }

        return $postId;
    }

    public function deletePost($postId)
    {
        if (!$postId) {
            return json_encode([
                'res' => false,
                'msg' => "Missing post id"
            ]);
        }
        if (!$this->post->deletePost($postId)) {
            return json_encode([
                'res' => false,
                "msg" => "Couldn't delete the post"
            ]);
        }
        return json_encode([
            'res' => true,
            "msg" => "Post deleted successfully"
        ]);
    }

    public function countPosts()
    {
        return $this->post->countPosts();
    }
}
