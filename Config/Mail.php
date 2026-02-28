<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\OAuth;

// Error logging configuration
ini_set('display_errors', 0);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/../../error.log');
error_reporting(E_ALL);


require __DIR__ . '/../vendor/autoload.php';


$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();


function sendMail($to, $subject, $body, $altBody = '')
{
    try {
        $mail = new PHPMailer(true);

        // SMTP settings
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;
        $mail->AuthType   = 'XOAUTH2';

        // Load credentials
       $clientId     = $_ENV['GOOGLE_CLIENT_ID'] ?? null;
        $clientSecret = $_ENV['GOOGLE_CLIENT_SECRET'] ?? null;
    $refreshToken = $_ENV['GOOGLE_REFRESH_TOKEN'] ?? null;
     $email        = $_ENV['GOOGLE_EMAIL'] ?? null;


        // Debug logging
        error_log("GOOGLE_EMAIL from .env: " . var_export($email, true));

        // Check for missing credentials
        if (empty($clientId) || empty($clientSecret) || empty($refreshToken) || empty($email)) {
            throw new Exception("One or more required OAuth2 credentials are missing.");
        }

        $provider = new League\OAuth2\Client\Provider\Google([
            'clientId'     => $clientId,
            'clientSecret' => $clientSecret,
        ]);

        $mail->setOAuth(new OAuth([
            'provider'     => $provider,
            'clientId'     => $clientId,
            'clientSecret' => $clientSecret,
            'refreshToken' => $refreshToken,
            'userName'     => $email,
        ]));

        // Sender and receiver
        $mail->setFrom($email, 'AriPen');
        $mail->addReplyTo($email, 'AriPen');
        $mail->addAddress($to);

        // Email content
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = $body;
        $mail->AltBody = $altBody ?: strip_tags($body);


        $mail->send();
        return true;

    } catch (Throwable $e) {
     error_log("ERROR in sendMail(): " . $e->getMessage());
     error_log("In file: " . $e->getFile() . " on line " . $e->getLine());
     return $e->getMessage();
    }

}
?>
