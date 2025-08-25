<?php
require_once __DIR__ . '/../../config/php/database.php';
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
        if (!$decodedData->password) {
            $updatedData->password = $user->password;
        }
        if (!$this->user->updateUser($updatedData)) {
            return json_encode([
                'res' => false,
                'msg' => "Error updating user"
            ]);
        }
        return [
            'res' => true,
            'msg' => "User updated"
        ];

    }
}
