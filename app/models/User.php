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
        // echo "PASS" . $data->password;
        // echo password_get_info($data->password)['algo'] !== 2y ? "HOLA"  : "FIN";
        // echo "HASSS" . password_get_info($data->password)['algo'];
        // return false;
        $a = password_hash("geeksforgeeks", PASSWORD_DEFAULT);
        var_dump(password_get_info($a));        
        // $hashed_pass = (password_get_info($data->password)['algo'] !== "2y")
        //     ? $data->password
        //     : password_hash($data->password, PASSWORD_DEFAULT);

        $hashed_pass = password_hash($data->password, PASSWORD_DEFAULT);
        $stmt = $this->connection->prepare($query);
        $stmt->bindParam(':username', $data->username);
        $stmt->bindParam(':password', $hashed_pass, PDO::PARAM_STR);
        $stmt->bindParam(':email', $data->email);
        $emailPreference = 1;
        $stmt->bindParam(':emailPreference', $emailPreference, PDO::PARAM_INT);
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
}
