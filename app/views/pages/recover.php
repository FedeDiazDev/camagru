<?php

$token = $_GET['token'] ?? null;

if (!$token) {
    die('Invalid link');
}

$user = $userModel->getUserByResetToken($token);
if (!$user) {
    die('Invalid or expired token');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $password = $_POST['password'];
    $confirm = $_POST['confirm'];
    if ($password === $confirm) {
        $userModel->updatePassword($user['id'], $password);
        echo "Password updated successfully!";
    } else {
        echo "Passwords don't match.";
    }
}
