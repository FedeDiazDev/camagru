<?php
//   findByUsername($username)
//      findByEmail($email)
//      verifyUser($email, $password)
//      updateUser($id, $data)
//      deleteUser($id)
class User
{
    private $connection;

    public function __construct($db)
    {
        $this->connection = $db;
    }

    public function getUserById($id)
	{
		$query = "SELECT * FROM user WHERE id = :id";
		$stmt = $this->connection->prepare($query);
		$stmt->bindParam(':id', $id, PDO::PARAM_INT);
		$stmt->execute();

		return $stmt->fetch(PDO::FETCH_OBJ);
	}

    public function createUser($username, $email, $password)
    {
        $token = bin2hex(random_bytes(50));
        $hashed_pass = password_hash($password, PASSWORD_DEFAULT);
        $query = "INSERT INTO user (username, email, password, cofirmationToken) VALUES (:username, :email, :password, :token)";
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
}
