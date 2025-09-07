<?php

require_once __DIR__ . '/../../config/php/database.php';
require_once __DIR__ . '/../models/Like.php';

class LikeController
{
    private $like;

    public function __construct()
    {
        $database = new Database();
        $this->like = new User($database->connect());
    }

    public function getLikesbyPost($postId){
        if (!$postId){
            return json_encode([
                'res' => false,
                'msg' => "Missing id"
            ]);
        }
        $likes = $this->like->getLikesbyPost($postId);
        if (!$likes)
        {
            return json_encode([
                'res' => false,
                'msg' => "Couldn't get number of likes"
            ]);
        }
        return json_encode([
            'res' => true,
            'msg' => $likes
        ]);
    }

    public function like_unlike($postId, $userId, $liked){
        if ($liked){
            return $this->like->removeLike($postId);
        }
        return $this->like->addLike($postId, $userId);
    }
}