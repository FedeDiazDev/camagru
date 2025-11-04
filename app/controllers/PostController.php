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
            return false;
        }
        printf("EEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEE");

        $dir = __DIR__ . '/../../public/images/posts/';
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }

        $imageData = explode(',', $baseImage);
        if (count($imageData) !== 2) {
            return false;
        }

        $decodedBase = base64_decode(str_replace(' ', '+', $imageData[1]));
        if ($decodedBase === false) {
            return false;
        }

        $baseImg = imagecreatefromstring($decodedBase);
        if (!$baseImg) {
            return false;
        }

        foreach ($stickers as $sticker) {
            $stickerPath = __DIR__ . '/../../public/images/stickers/' . basename($sticker['src']);
            if (!file_exists($stickerPath)) continue;

            $stickerImg = imagecreatefrompng($stickerPath);
            if (!$stickerImg) continue;

            $width = $sticker['width'] ?? imagesx($stickerImg);
            $height = $sticker['height'] ?? imagesy($stickerImg);

            $resized = imagecreatetruecolor($width, $height);
            imagealphablending($resized, false);
            imagesavealpha($resized, true);
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
            case 'grayscale':
                imagefilter($baseImg, IMG_FILTER_GRAYSCALE);
                break;
            case 'sepia':
                imagefilter($baseImg, IMG_FILTER_GRAYSCALE);
                imagefilter($baseImg, IMG_FILTER_COLORIZE, 90, 60, 30);
                break;
            case 'invert':
                imagefilter($baseImg, IMG_FILTER_NEGATE);
                break;
        }
        $filename = 'post_' . time() . '_' . $userId . '.png';
        $filePath = $dir . $filename;
        imagepng($baseImg, $filePath);
        imagedestroy($baseImg);
        $relativePath = '/images/posts/' . $filename;
        printf("HOLAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA");
        return $this->post->createPost($userId, $title, $relativePath);
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
