<?php
class Post
{
    private $connection;

    public function __construct($db)
    {
        $this->connection = $db;
    }

    public function countPosts()
    {
        try {
            $query = "SELECT COUNT(*) as total FROM post";
            $stmt = $this->connection->prepare($query);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            return (int)($row['total'] ?? 0);
        } catch (PDOException $e) {
            return 0;
        }
    }


    public function getPostById($id)
    {
        $query = "SELECT p.id,
        p.userId,
        p.date,
        p.mediaUrl,
        p.title,
        u.username AS author,
        COUNT(l.id) AS likes
        FROM post p
        JOIN user u ON p.userId = u.id
        LEFT JOIN likes l ON l.postId = p.id
        WHERE p.id = :id
        GROUP BY p.id, p.userId, p.date, p.mediaUrl, p.title, u.username;";
        $stmt = $this->connection->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $post = $stmt->fetch(PDO::FETCH_OBJ);
        $queryComments = "SELECT c.id,
                             c.content,
                             c.date,
                             u.username                            
                      FROM comment c
                      JOIN user u ON c.userComment = u.id
                      WHERE c.postId = :id
                      ORDER BY c.date DESC";

        $stmt = $this->connection->prepare($queryComments);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $comments = $stmt->fetchAll(PDO::FETCH_OBJ);

        $post->comments = $comments;

        return $post;
    }

    public function getPosts($limit, $offset)
    {
        try {
            $query = "SELECT p.id,
                         p.userId,
                         p.date,
                         u.username AS author,
                         COUNT(l.id) AS likes
                  FROM post p
                  JOIN user u ON p.userId = u.id
                  LEFT JOIN likes l ON l.postId = p.id
                  GROUP BY p.id, p.userId, p.date, u.username
                  ORDER BY p.date DESC
                  LIMIT :limit OFFSET :offset;";

            $stmt = $this->connection->prepare($query);
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
            $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
            $stmt->execute();

            $posts = $stmt->fetchAll(PDO::FETCH_OBJ);

            foreach ($posts as $post) {
                $post->mediaUrl = "/get_image.php?id=" . $post->id;
            }

            return $posts;
        } catch (PDOException $e) {
            return false;
        }
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
        $stmt->bindParam(':mediaUrl', $mediaUrl, PDO::PARAM_LOB);

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
