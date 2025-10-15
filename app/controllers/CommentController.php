<?php
require_once __DIR__ . '/../../config/php/database.php';
require_once __DIR__ . '/../models/Comment.php';
require_once __DIR__ . '/../controllers/PostController.php';
require_once __DIR__ . '/../controllers/UserController.php';
require_once __DIR__ . '/../testmail.php';

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
            $postController = new PostController();
            $userController = new UserController();
            $post = json_decode($postController->getPostById($postId));
            $user = json_decode($userController->getUserById($post->author));
            $commenter = json_decode($userController->getUserById($$userCommentId));
            if ($user->emailPreference)
            {
                if (!sendCommentNotification($user->email, $user->username, $commenter->username,$post->title, "http://localhost:8081/post?id=$post->id"))
                {
                    return json_encode([
                    'res' => false,
                    'msg' => "Error sending notification"
                ]);
            }
            }
            return json_encode([
                'res' => true,
                'msg' => "Comment added"
            ]);
        }

        return json_encode([
            'res' => false,
            'msg' => "Couldn't post the comment"
        ]) ;
    }
}
