<?php
class Post
{
    private $connection;

    public function __construct($db)
    {
        $this->connection = $db;
    }

    public function getPostById($id)
    {
        $query = "SELECT * FROM `post` WHERE id = :id";
        $stmt = $this->connection->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    public function getPosts($limit, $offset)
    {
        // $query = "SELECT * FROM post";
        $query = "SELECT p.id,
        p.userId,
        p.date,
        p.mediaUrl,
        u.username AS author,
        COUNT(l.id) AS likes
            FROM post p
            JOIN user u ON p.userId = u.id
            LEFT JOIN likes l ON l.postId = p.id
            GROUP BY p.id, p.userId, p.date, p.mediaUrl, u.username
            ORDER BY p.date DESC
            LIMIT :limit OFFSET :offset;";

        $stmt = $this->connection->prepare($query);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }


    public function getUserPosts($userId, $limit, $offset)
    {
        $query = "SELECT p.*, u.username AS author,
                     COUNT(l.id) AS likes
              FROM post p
              JOIN user u ON p.userId = u.id
              LEFT JOIN likes l ON l.postId = p.id
              WHERE p.userId = :userId
              GROUP BY p.id, u.username
              ORDER BY p.date DESC
              LIMIT :limit OFFSET :offset";

        $stmt = $this->connection->prepare($query);
        $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function createPost($userId, $title, $mediaUrl)
    {
        $query = "INSERT INTO post (userId, title, date, mediaUrl)
              VALUES (:userId, :title, NOW(), :mediaUrl)";
        $stmt = $this->connection->prepare($query);

        $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
        $stmt->bindParam(':title', $title, PDO::PARAM_STR);
        $stmt->bindParam(':mediaUrl', $mediaUrl, PDO::PARAM_STR);

        $stmt->execute();
        return $this->connection->lastInsertId();
    }


    public function deletePost($postId)
    {

        $query = "DELETE FROM likes WHERE postId = :postId";
        $stmt = $this->connection->prepare($query);
        $stmt->bindParam(':postId', $postId, PDO::PARAM_INT);
        $stmt->execute();

        $query = "DELETE FROM comment WHERE postId = :postId";
        $stmt = $this->connection->prepare($query);
        $stmt->bindParam(':postId', $postId, PDO::PARAM_INT);
        $stmt->execute();

        $query = "DELETE FROM post WHERE id = :postId";
        $stmt = $this->connection->prepare($query);
        $stmt->bindParam(':postId', $postId, PDO::PARAM_INT);
        $stmt->execute();
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
}
