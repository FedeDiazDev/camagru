<?php
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../models/User.php';

class UserController{
    private $user;

    public function __construct() {
        $database = new Database();
        $this->user = new User($database->connect());
    }

    public function register($username, $email, $password, $confirmPassword)
    {
        if ($email != $confirmPassword)
            return ("Password don't match");
        else if (empty($usename) || empty($email) || empty($password) || empty($confirmPassword))
            return ("Empty fields");
        else if ($this->user->getUserByEmail($email))
            return ("Email already registered");
        else if($this->user->getUserByUsername($usename))
            return ("Username already registered");
        
        if ($this->user->createUser($usename, $email, $password)){
            //enviarMail
            // if (!enviarMail)
                return[false, "Error sending email confirmation"];
            return true;
        }
        return [false, "Error registering user"];
    }
}