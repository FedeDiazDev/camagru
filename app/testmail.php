<?php
require __DIR__ . '/../../vendor/autoload.php';

function sendVerificationEmail($toEmail, $token)
{
    $email = new \SendGrid\Mail\Mail();
    $email->setFrom("federicojose2000@gmail.com", "Camagru");
    $email->setSubject("Confirma tu correo electrónico");
    $email->addTo($toEmail);
    $email->addContent(
        "text/plain",
        "Haz clic en el siguiente enlace para confirmar tu cuenta: http://localhost:8081/verify?token=$token"
    );
    $sendgrid = new \SendGrid(getenv('SENDGRID_API_KEY'));
    try {
        $response = $sendgrid->send($email);        
        return $response->statusCode() === 202;        
    } catch (Exception $e) {
        echo 'Caught exception: ' . $e->getMessage() . "\n";
        return false;
    }
}

//? enviar token
function recoverPassword ($toEmail, $token)
{
    $email = new \SendGrid\Mail\Mail();
    $email->setFrom("federicojose2000@gmail.com", "Camagru");
    $email->setSubject("Recupera tu contraseña");
    $email->addTo($toEmail);
    $email->addContent(
        "text/plain",
        "Haz click en el siguiente enlace para confirmar tu cuenta http:///localhost:8081/recover?token=$token"
    );
    $sendgrid = new \SendGrid(getenv('SENDGRID_API_KEY'));
    try
    {
        $response = $sendgrid->send($email);
        return $response->statusCode() === 202;
    } catch(Exception $e) {
        echo 'Caught exception: ' . $e->getMessage() . "\n";
        return false;
    }
}