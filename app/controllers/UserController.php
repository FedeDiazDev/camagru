<?php
require_once __DIR__ . '/../../config/php/database.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../testmail.php';
class UserController
{
    private $user;

    public function __construct()
    {
        $database = new Database();
        $this->user = new User($database->connect());
    }


    public function getUserById($id)
    {
        if (!$id) {
            return json_encode([
                'res' => false,
                'msg' => "Missing id"
            ]);
        }
        $user = $this->user->getUserById($id);
        if (!$user) {
            return json_encode([
                'res' => false,
                'msg' => "User doesn't exists"
            ]);
        }
        return json_encode([
            'res' => true,
            'msg' => [
                'id' => $user->id,
                'username' => $user->username,
                'email' => $user->email,
                'notification' => $user->emailPreference
            ]
        ]);
    }
    public function register($username, $email, $password, $confirmPassword)
    {
        if ($password != $confirmPassword) {
            echo json_encode([
                'res' => false,
                'msg' => "Passwords don't match"
            ]);
            exit;
        }

        if (empty($username) || empty($email) || empty($password) || empty($confirmPassword)) {
            echo json_encode([
                'res' => false,
                'msg' => "Empty fields"
            ]);
            exit;
        }

        if ($this->user->getUserByEmail($email)) {
            echo json_encode([
                'res' => false,
                'msg' => "Email already registered"
            ]);
            exit;
        }

        if ($this->user->getUserByUsername($username)) {
            echo json_encode([
                'res' => false,
                'msg' => "Username already registered"
            ]);
            exit;
        }

        if ($this->user->createUser($username, $email, $password)) {
            $user = $this->user->getUserByUsername($username);
            sendVerificationEmail($user->email, $user->confirmationToken);            
            exit;
        }

        echo json_encode([
            'res' => false,
            'msg' => "Error registering user"
        ]);
        exit;
    }


    public function logIn($username, $password)
    {
        if (empty($username) || empty($password)) {
            return ['res' => false, 'msg' => "Empty fields"];
        }
        if (!$this->user->getUserByUsername($username)) {
            return ['res' => false, 'msg' => "User doesn't exist"];
        }
        $user = $this->user->getUserByUsername($username);
        if (!password_verify($password, $user->password)) {
            return ['res' => false, 'msg' => "Wrong password"];
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

    //username, email, (password, new password(optional)) 
    public function updateUser($data)
    {
        $decodedData = json_decode($data);
        if (empty($decodedData->username) || empty($decodedData->email))
            return json_encode([
                'res' => false,
                'msg' => "Empty fields"
            ]);

        $user = $this->user->getUserById($decodedData->id);
        if ($decodedData->password && !password_verify($decodedData->currentPassword, $user->password)) {
            return json_encode([
                'res' => false,
                'msg' => "Password doesn't match"
            ]);

        }
        /**
         * * Si dejan otro campo vacío(email o username, ignorar y dejar los default?)
         * ? Verificar si quiere o no cambiar contraseña
         * * Si los campos están vacíos, ignorarlos?
         * * Cómo se cuando cuando están vacíos a propósito y cuando no
         * * si rellenan la contraseña actual es obligatorio que rellenen las otras? o simplemente ignorarlo?
         */
        if ($this->user->getUserByUsername($decodedData->username)) {
            return json_encode([
                'res' => false,
                'msg' => "Username already exists"
            ]);
        }

        if ($this->user->getUserByEmail($decodedData->email)) {
            return json_encode([
                'res' => false,
                'msg' => "Email already exists"
            ]);
        }

        $updatedData = new stdClass();
        $updatedData->id = $user->id;
        $updatedData->email = $decodedData->email;
        $updatedData->username = $decodedData->username;
        $updatedData->password = $decodedData->password ? $decodedData->password : $user->password;
        $updatedData->notifications = $decodedData->notifications;

        if (!$this->user->updateUser($updatedData)) {
            return json_encode([
                'res' => false,
                'msg' => "Error updating user"
            ]);
        }
        return json_encode([
            'res' => true,
            'msg' => "User updated"
        ]);

    }
}
