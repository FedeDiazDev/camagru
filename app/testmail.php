<?php
require __DIR__ . '/../../vendor/autoload.php';

function sendVerificationEmail($toEmail, $token)
{
  $email = new \SendGrid\Mail\Mail();
  $email->setFrom("federicojose2000@gmail.com", "Camagru");
  $email->setSubject("Confirma tu correo electrónico");
  $email->addTo($toEmail);
  $email->addContent(
    "text/html",
    "
    <!DOCTYPE html>
    <html lang='es'>
      <head>
        <meta charset='UTF-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        <title>Confirma tu cuenta - Camagru</title>
      </head>
      <body style='margin:0; padding:0; background-color:#0f0a1a; font-family:Arial, Helvetica, sans-serif; color:#ddd;'>
        <table width='100%' cellpadding='0' cellspacing='0' style='max-width:600px; margin:40px auto; background-color:#1a1325; border-radius:10px; overflow:hidden; box-shadow:0 0 20px rgba(128,0,128,0.3);'>
          <tr>
            <td style='background:linear-gradient(90deg, #7e22ce, #ec4899); padding:20px; text-align:center;'>
              <h1 style='color:white; font-size:28px; margin:0; font-weight:700; letter-spacing:1px;'>Camagru</h1>
            </td>
          </tr>
          <tr>
            <td style='padding:40px 30px; text-align:center;'>
              <h2 style='color:#fff; font-size:22px; margin-bottom:10px;'>Confirma tu correo electrónico</h2>
              <p style='color:#b3a8c9; font-size:15px; line-height:1.6; margin-bottom:25px;'>
                ¡Bienvenido a la comunidad de <strong>Camagru</strong>!<br>
                Solo falta un paso para activar tu cuenta y comenzar a crear.
              </p>

              <a href='http://localhost:8081/verify?token=$token'
                style='display:inline-block; background:linear-gradient(90deg, #7e22ce, #ec4899); color:white; text-decoration:none; padding:14px 28px; border-radius:8px; font-weight:bold; font-size:15px; letter-spacing:0.5px;'>
                Confirmar mi cuenta
              </a>

              <p style='color:#9d8fb8; font-size:13px; line-height:1.6; margin-top:25px;'>
                Si el botón no funciona, copia y pega el siguiente enlace en tu navegador:<br>
                <a href='http://localhost:8081/verify?token=$token' style='color:#ec4899; text-decoration:none;'>http://localhost:8081/verify?token=$token</a>
              </p>
            </td>
          </tr>
          <tr>
            <td style='background-color:#120d1d; padding:20px; text-align:center; color:#777; font-size:12px;'>
              <p style='margin:0;'>© 2025 Camagru. Todos los derechos reservados.</p>
            </td>
          </tr>
        </table>
      </body>
    </html>
    "
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

function recoverPassword($toEmail, $token)
{
  $email = new \SendGrid\Mail\Mail();
  $email->setFrom("federicojose2000@gmail.com", "Camagru");
  $email->setSubject("Recupera tu contraseña");
  $email->addTo($toEmail);
  $email->addContent(
    "text/html",
    "<html>
        <body style='font-family: Arial, sans-serif; background-color: #f9f9f9; margin: 0; padding: 40px;'>
            <div style='max-width: 600px; margin: auto; background: #ffffff; border-radius: 8px; padding: 20px; box-shadow: 0 2px 6px rgba(0,0,0,0.1);'>
                <h2 style='color: #333;'>Recupera tu contraseña</h2>
                <p style='font-size: 16px; color: #555;'>
                    Hemos recibido una solicitud para restablecer la contraseña de tu cuenta en <strong>Camagru</strong>.
                </p>
                <p style='font-size: 16px; color: #555;'>
                    Si fuiste tú quien solicitó el cambio, haz clic en el botón de abajo para crear una nueva contraseña:
                </p>
                <div style='text-align: center; margin: 30px 0;'>
                    <a href='http:///localhost:8081/recover?token=$token' 
                        style='background-color: #007BFF; color: white; padding: 12px 24px; border-radius: 6px; text-decoration: none; font-weight: bold;'>
                        Restablecer contraseña
                    </a>
                </div>
                <p style='font-size: 14px; color: #888;'>
                    Si no solicitaste este cambio, puedes ignorar este mensaje. Tu contraseña seguirá siendo la misma.
                </p>
                <hr style='border: none; border-top: 1px solid #eee; margin: 20px 0;'>
                <p style='font-size: 12px; color: #aaa; text-align: center;'>
                    © " . date('Y') . " Camagru. Todos los derechos reservados.
                </p>
            </div>
        </body>
        </html>"
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

function sendCommentNotification($toEmail, $username, $commenter, $postTitle, $postLink)
{
  $email = new \SendGrid\Mail\Mail();
  $email->setFrom("federicojose2000@gmail.com", "Camagru");
  $email->setSubject("¡$commenter ha comentado tu post!");
  $email->addTo($toEmail);
  $email->addContent(
    "text/html",
    "
    <!DOCTYPE html>
    <html lang='es'>
      <head>
        <meta charset='UTF-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        <title>Nuevo comentario en tu publicación</title>
      </head>
      <body style='margin:0; padding:0; background-color:#0f0a1a; font-family:Arial, Helvetica, sans-serif; color:#ddd;'>
        <table width='100%' cellpadding='0' cellspacing='0' style='max-width:600px; margin:40px auto; background-color:#1a1325; border-radius:10px; overflow:hidden; box-shadow:0 0 20px rgba(128,0,128,0.3);'>
          <tr>
            <td style='background:linear-gradient(90deg, #7e22ce, #ec4899); padding:20px; text-align:center;'>
              <h1 style='color:white; font-size:28px; margin:0; font-weight:700; letter-spacing:1px;'>Camagru</h1>
            </td>
          </tr>
          <tr>
            <td style='padding:40px 30px; text-align:center;'>
              <h2 style='color:#fff; font-size:22px; margin-bottom:10px;'>¡Tienes un nuevo comentario!</h2>
              <p style='color:#b3a8c9; font-size:15px; line-height:1.6; margin-bottom:25px;'>
                Hola <strong>$username</strong>,<br>
                <strong>$commenter</strong> ha comentado tu publicación <strong>\"$postTitle\"</strong>.
              </p>

              <a href='$postLink'
                style='display:inline-block; background:linear-gradient(90deg, #7e22ce, #ec4899); color:white; text-decoration:none; padding:14px 28px; border-radius:8px; font-weight:bold; font-size:15px; letter-spacing:0.5px;'>
                Ver comentario
              </a>

              <p style='color:#9d8fb8; font-size:13px; line-height:1.6; margin-top:25px;'>
                Si el botón no funciona, copia y pega este enlace en tu navegador:<br>
                <a href='$postLink' style='color:#ec4899; text-decoration:none;'>$postLink</a>
              </p>
            </td>
          </tr>
          <tr>
            <td style='background-color:#120d1d; padding:20px; text-align:center; color:#777; font-size:12px;'>
              <p style='margin:0;'>© " . date('Y') . " Camagru. Todos los derechos reservados.</p>
            </td>
          </tr>
        </table>
      </body>
    </html>
    "
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
