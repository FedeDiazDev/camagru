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
                'url' => $post->mediaUrl
            ]
        ]);
    }

    public function getPosts($limit, $offset)
    {
        if (!$limit || !$offset) {
            return json_encode([
                'res' => false,
                'msg' => "Error on pagination"
            ]);
        }
        $posts = $this->post->getPosts($limit, $offset);
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

    public function createPost($userId, $title)
    {
        if (!$userId) {
            return json_encode([
                'res' => false,
                'msg' => "Missing user id"
            ]);
        }
        //TODO: mediaURL
        $mediaUrl = "";
        if (empty($title)) {
            return json_encode([
                'res' => false,
                'msg' => "Missing title"
            ]);
        }
        $userController = new UserController();
        if (!$userController->getUserById($userId)) {
            return json_encode([
                'res' => false,
                'msg' => "User doesn't exists"
            ]);
        }

        $input = json_decode(file_get_contents('php://input'), true);
        $imageData = $input['image'] ?? null;
        if (!$imageData) {
            return json_encode([
                'res' => false,
                'msg' => "Missing image"
            ]);
        }

        $img = str_replace('data:image/png;base64,', '', $imageData);
        $img = str_replace(' ', '+', $img);
        $data = base64_decode($img);
        
        $fileName = 'uploads/' . uniqid('camagru_') . '.png';
        file_put_contents($fileName, $data);

        $mediaUrl = $fileName;

        if ($this->post->createPost($userId, $title, $mediaUrl)) {
            return json_encode([
                'res' => true,
                'msg' => "Post created"
            ]);
        }
        return json_encode([
            'res' => false,
            'msg' => "Error creating post"
        ]);
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
}
