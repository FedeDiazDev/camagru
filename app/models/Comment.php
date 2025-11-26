<?php

class Comment
{
    private $connection;

    public function __construct($db)
    {
        $this->connection = $db;
    }

    public function addComment($postId, $comment, $userCommentId)
    {
        $query = "INSERT INTO comment (postId, content, userComment, date) VALUES (:postId, :comment, :userCommentId,  NOW() )";
        $stmt = $this->connection->prepare($query);
        $stmt->bindParam(':postId', $postId, PDO::PARAM_INT);
        $stmt->bindParam(':comment', $comment);
        $stmt->bindParam(':userCommentId', $userCommentId, PDO::PARAM_INT);
        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    public function getCommentsByPost($postId)
    {
        $query = "SELECT comment.*, u.username AS user FROM comment JOIN user u ON comment.userComment = u.id WHERE comment.postId = :post_id; ";
        $stmt = $this->connection->prepare($query);
        $stmt->bindParam(':post_id', $postId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function deletePost($commentId)
    {
        $query = "DELETE FROM comment WHERE id = :commenterId";
        $stmt = $this->connection->prepare($query);
        $stmt->bindParam(':commenterId', $commentId, PDO::PARAM_INT);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }


    public function deleteComment($commentId, $userId)
    {
        $queryCheck = "SELECT id FROM comment WHERE id = :id AND userComment = :userId";
        $stmtCheck = $this->connection->prepare($queryCheck);
        $stmtCheck->bindParam(':id', $commentId, PDO::PARAM_INT);
        $stmtCheck->bindParam(':userId', $userId, PDO::PARAM_INT);
        $stmtCheck->execute();

        if ($stmtCheck->rowCount() > 0) {
            $query = "DELETE FROM comment WHERE id = :id";
            $stmt = $this->connection->prepare($query);
            $stmt->bindParam(':id', $commentId, PDO::PARAM_INT);

            if ($stmt->execute()) {
                return true;
            }
        }
        return false;
    }
}
