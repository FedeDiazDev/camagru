<?php
class User
{
    private $connection;

    public function __construct($db)
    {
        $this->connection = $db;
    }

    public function getLikesbyPost($postId)
    {
        $query = "SELECT COUNT (*) FROM likes WHERE postId : postId";
        $stmt = $this->connection->prepare($query);
        $stmt->bindParam(':postId', $postId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    public function addLike($postId, $userId)
    {
        $query = "INSERT INTO likes (postId, userId) VALUES (:postId, :userId)";
        $stmt = $this->connection->prepare($query);
        $stmt->bindParam('postId', $postId, PDO::PARAM_INT);
        $stmt->bindParam('userId', $userId, PDO::PARAM_INT);
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function removeLike($postId)
    {
        $query = "DELETE FROM likes WHERE postId = :postId";
        $stmt = $this->connection->prepare($query);
        $stmt->bindParam(":postId", $postId, PDO::PARAM_INT);
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
}
