<?php

class User
{
    private $connection;

    public function __construct($db)
    {
        $this->connection = $db;
    }

    public function getUserById($id)
    {
        $query = "SELECT * FROM `user` WHERE id = :id";
        $stmt = $this->connection->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    public function getUserByUsername($username)
    {
        $query = "SELECT * FROM `user` WHERE username = :username";
        $stmt = $this->connection->prepare($query);
        $stmt->bindParam(':username', $username);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    public function getUserByEmail($email)
    {
        $query = "SELECT * FROM `user` WHERE email = :email";
        $stmt = $this->connection->prepare($query);
        if (!$stmt) {
            var_dump($this->connection->errorInfo());
            return null;
        }

        $stmt->bindParam(':email', $email);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    public function createUser($username, $email, $password)
    {
        $token = bin2hex(random_bytes(50));
        $hashed_pass = password_hash($password, PASSWORD_DEFAULT);
        $query = "INSERT INTO `user` (username, email, password, confirmationToken) VALUES (:username, :email, :password, :token)";
        $stmt = $this->connection->prepare($query);

        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hashed_pass);
        $stmt->bindParam(':token', $token);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function updateUser($data)
    {
        $query = "UPDATE user set username = :username, email = :email, password = :password, emailPreference = :emailPreference WHERE id = :id";
        $hashed_pass = password_hash($data->password, PASSWORD_DEFAULT);
        $stmt = $this->connection->prepare($query);
        $stmt->bindParam(':username', $data->username);
        $stmt->bindParam(':password', $hashed_pass, PDO::PARAM_STR);
        $stmt->bindParam(':email', $data->email);
        $stmt->bindParam(':emailPreference', $data->notifications, PDO::PARAM_INT);
        $stmt->bindParam(':id', $data->id);
        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    public function deleteUser($id)
    {
        $query = "DELETE FROM user WHERE id = :id";
        $stmt = $this->connection->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }


    public function verifyEmail($confirmationToken)
    {
        $stmt = $this->connection->prepare("UPDATE user SET emailConfirmed = 1, confirmationToken = NULL WHERE confirmationToken = :confirmationToken");
        $stmt->bindParam(':confirmationToken', $confirmationToken);
        $stmt->execute();
        return $stmt->rowCount() > 0;
    }

    public function setPasswordResetToken($email, $token, $expires)
    {
        try {
            $stmt = $this->connection->prepare("
            UPDATE user 
            SET reset_token = ?, reset_expires = ? 
            WHERE email = ?
        ");
            $stmt->execute([$token, $expires, $email]);

            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            error_log("Error setting password reset token: " . $e->getMessage());
            return false;
        }
    }

    public function getUserByToken($token)
    {
        $stmt = $this->connection->prepare("SELECT * FROM user WHERE confirmationToken = :token");
        $stmt->bindParam(':token', $token);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_OBJ);
        return $user ? $user : false;
    }


    public function getUserByResetToken($token)
    {
       $stmt = $this->connection->prepare("SELECT * FROM user WHERE reset_token = :token");
        $stmt->bindParam(':token', $token);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_OBJ);
        return $user ? $user : false;
    }

    public function updatePassword($userId, $newPassword)
    {
        $hashed_pass = password_hash($newPassword, PASSWORD_DEFAULT);
        $stmt = $this->connection->prepare("UPDATE user SET password = :password, reset_token = NULL, reset_expires = NULL WHERE id = :id");
        $stmt->bindParam(':password', $hashed_pass, PDO::PARAM_STR);
        $stmt->bindParam(':id', $userId);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
}
