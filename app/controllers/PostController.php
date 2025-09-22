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
        if ($limit <= 0|| $offset < 0) {
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

    public function createPost($userId, $title, $mediaUrl)
    {
        if (!$userId) {
            return false;
        }
        if (empty($title)) {
            return false;
        }

        $userController = new UserController();
        if (!$userController->getUserById($userId)) {
            return false;
        }

        return $this->post->createPost($userId, $title, $mediaUrl);
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
