<?php
class Like
{
    private $connection;

    public function __construct($db)
    {
        $this->connection = $db;
    }

    public function getLikesbyPost($postId)
    {
        $query = "SELECT COUNT(*) as total FROM likes WHERE postId = :postId";
        $stmt = $this->connection->prepare($query);
        $stmt->bindParam(':postId', $postId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    public function addLike($postId, $userId)
    {
        $query = "INSERT INTO likes (postId, userId, date) VALUES (:postId, :userId, NOW())";
        $stmt = $this->connection->prepare($query);
        $stmt->bindParam('postId', $postId, PDO::PARAM_INT);
        $stmt->bindParam('userId', $userId, PDO::PARAM_INT);
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
    public function hasLiked($postId, $userId)
    {
        $query = "SELECT 1 FROM likes WHERE postId = :postId AND userId = :userId";
        $stmt = $this->connection->prepare($query);
        $stmt->bindParam(":postId", $postId, PDO::PARAM_INT);
        $stmt->bindParam(":userId", $userId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch() !== false;
    }
    public function removeLike($postId, $userId)
    {
        $query = "DELETE FROM likes WHERE postId = :postId AND userId = :userId";
        $stmt = $this->connection->prepare($query);
        $stmt->bindParam(":postId", $postId, PDO::PARAM_INT);
        $stmt->bindParam(":userId", $userId, PDO::PARAM_INT);
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
}
