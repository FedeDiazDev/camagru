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
        printf("Response status: %d\n\n", $response->statusCode());
        $headers = array_filter($response->headers());
        echo "Response Headers\n\n";
        foreach ($headers as $header) {

            echo '- ' . $header . "\n";
        }
    } catch (Exception $e) {
        echo 'Caught exception: ' . $e->getMessage() . "\n";
    }
}
