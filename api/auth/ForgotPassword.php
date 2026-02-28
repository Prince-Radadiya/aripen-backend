<?php

ini_set('display_errors', 0); // Don't show errors in the browser
ini_set('log_errors', 1);     // Enable logging
ini_set('error_log', __DIR__ . '/../../error.log'); // Path to your custom log file
error_reporting(E_ALL);       // Report all types of errors (strict, notices, warnings, etc.)


require_once __DIR__ . '/../../Config/Db.php';     // MongoDB connection
require_once __DIR__ . '/../../Config/Mail.php'; // PHPMailer setup

header("Access-Control-Allow-Origin: http://localhost:5173");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}


$data = json_decode(file_get_contents("php://input"), true);
$email = trim($data['email'] ?? '');

if (!$email) {
    echo json_encode(["success" => false, "message" => "Email is required"]);
    exit;
}

// ✅ Find user (case-insensitive)
$user = $db->User->findOne([
    "email" => ['$regex' => "^" . preg_quote($email) . "$", '$options' => 'i']
]);

if (!$user) {
    echo json_encode([
        "success" => false,
        "message" => "No account found with that email",
        "email"   => $email
    ]);
    exit;
}

// ✅ Generate token
$token  = bin2hex(random_bytes(32));
$expiry = new MongoDB\BSON\UTCDateTime((time() + 900) * 1000); // 15 minutes

// ✅ Update using same regex filter
$db->User->updateOne(
    ["email" => ['$regex' => "^" . preg_quote($email) . "$", '$options' => 'i']],
    ['$set' => [
        "reset_token"  => $token,
        "reset_expiry" => $expiry
    ]]
);

$resetLink = "http://localhost:5173/reset-password?token=" . $token;

// ✅ Send email via PHPMailer (Mailer.php)
$subject = "Password Reset Request";
$body = "
    <h2>Password Reset</h2>
    <p>We received a request to reset your password.</p>
    <p><a href='$resetLink'>Click here to reset your password</a></p>
    <p>This link will expire in 15 minutes.</p>
";

$mailSent = sendMail($email, $subject, $body);

if ($mailSent === true) {
    echo json_encode([
        "success" => true,
        "message" => "Reset link sent to email",
        "resetLink" => $resetLink
    ]);
} else {
    // $mailSent contains the error message string here
    echo json_encode([
        "success" => false,
        "message" => "Failed to send reset email. Please try again.",
        "error" => $mailSent
    ]); 
}


?>