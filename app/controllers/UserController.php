<?php
require_once  __DIR__ .   '/../../config/php/database.php';
require_once __DIR__ . '/../models/User.php';

class UserController
{
    private $user;

    public function __construct()
    {
        $database = new Database();
        $this->user = new User($database->connect());
    }

    public function register($username, $email, $password, $confirmPassword)
    {
        if ($password != $confirmPassword) {
            return json_encode([
                'res' => false,
                'msg' => "Passwords don't match"
            ]);
        }

        if (empty($username) || empty($email) || empty($password) || empty($confirmPassword)) {
            return json_encode([
                'res' => false,
                'msg' => "Empty fields"
            ]);
        }

        if ($this->user->getUserByEmail($email)) {
            return json_encode([
                'res' => false,
                'msg' => "Email already registered"
            ]);
        }

        if ($this->user->getUserByUsername($username)) {
            return json_encode([
                'res' => false,
                'msg' => "Username already registered"
            ]);
        }

        if ($this->user->createUser($username, $email, $password)) {
            //TODO: enviar mail confirmacion
            return json_encode([
                'res' => true,
                'msg' => "User registered succesfully"
            ]);
        }

        return json_encode([
            'res' => false,
            'msg' => "Error registering user"
        ]);
    }

    public function logIn($username, $password)
    {
        if (empty($username) || empty($password)) {
            return (['res' => true, 'msg' => "Empty fields"]);
        }
        if (!$this->user->getUserByUsername($username)) {
            return (['res' => true, 'msg' => "User doesn't exist"]);
        }
        $user = $this->user->getUserByUsername($username);
        if (!password_verify($password, $user->password)) {
            return (['res' => true, 'msg' => "Wrong password"]);
        }
        return [
            'res' => true,
            'msg' => [
                'id' => $user->id,
                'username' => $user->username,
                'email' => $user->email
            ]
        ];
    }

    public function updateUser($data)
    {
        if ($data->username || empty($data->mail))
            return json_encode([
                'res' => false,
                'msg' => "Empty fields"
            ]);
    }
}
