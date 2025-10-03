<?php

require_once __DIR__ . '/../../config/php/database.php';
require_once __DIR__ . '/../models/Like.php';

class LikeController
{
    private $like;

    public function __construct()
    {
        $database = new Database();
        $this->like = new Like($database->connect());
    }

    public function     getLikesbyPost($postId)
    {
        if (!$postId) {
            return 0;
        }
        return  $this->like->getLikesbyPost($postId);
    }

    public function like_unlike($postId, $userId)
    {
        if ($this->like->hasLiked($postId, $userId)) {
            return $this->like->removeLike($postId, $userId);
        } else {
            return $this->like->addLike($postId, $userId);
        }
    }

    public function hasLiked($postID, $userId)
    {
        return $this->like->hasLiked($postID, $userId);
    }
}
