<?php
require_once __DIR__ . '/../../Config/Db.php';
header("Access-Control-Allow-Origin: http://localhost:5173");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");

$data = json_decode(file_get_contents("php://input"), true);
$token = $data['token'] ?? '';
$newPassword = $data['password'] ?? '';



if (!$token || !$newPassword) {
    echo json_encode(["success" => false, "message" => "Token and password required"]);
    exit;
}

// find user with token
$user = $db->User->findOne(["reset_token" => $token]);

if (!$user) {
    echo json_encode(["success" => false, "message" => "Invalid token"]);
    exit;
}

// check expiry
if ($user['reset_expiry']->toDateTime() < new DateTime()) {
    echo json_encode(["success" => false, "message" => "Token expired"]);
    exit;
}

// hash new password
$hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);

// update password & remove token
$result = $db->User->updateOne(
    ["reset_token" => $token],
    ['$set' => ["password" => $hashedPassword],
    '$unset' => ["reset_token" => "", "reset_expiry" => ""]]
);

if($result->getModifiedCount() > 0) {
    echo json_encode(["success" => true, "message" => "Password updated successfully"]);
} else {
    echo json_encode(["success" => false, "message" => "Password update failed"]);
}
