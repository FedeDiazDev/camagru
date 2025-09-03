<?php
require_once __DIR__ . '/../../config/php/database.php';
require_once __DIR__ . '/../models/Comment.php';

class CommentController
{
    private $comment;

    public function __construct()
    {
        $database = new Database();
        $this->comment = new Comment($database->connect());
    }

    public function getCommentsByPost($postId)
    {
        if (!$postId) {
            return json_encode([
                'res' => false,
                'msg' => "Missing postId"
            ]);
        }
        $comments = $this->comment->getCommentsByPost($postId);
        return json_encode([
            'res' => true,
            'msg' => $comments,
            'counter' => count($comments)
        ]);
    }

    public function deletePost($commentId)
    {
        if (!$commentId) {
            return json_encode([
                'res' => false,
                'msg' => "Missing commentId"
            ]);
        }
        return $this->comment->deletePost($commentId);
    }
    public function addComment($postId, $comment, $userCommentId)
    {
        if (!$postId) {
            return json_encode([
                'res' => false,
                'msg' => "Missing postId"
            ]);
        }
        if ($this->comment->addComment($postId, $comment, $userCommentId))
        {
            return json_encode([
                'res' => true,
                'msg' => 
            ]);
        }

        return json_encode([
            'res' => false,
            'msg' => 
        ]) ;
    }
}
