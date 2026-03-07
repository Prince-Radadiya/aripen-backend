<?php

// Use system temp for session storage
ini_set('session.save_path', sys_get_temp_dir());

// Session cookie settings: works for local & production (HTTPS optional)
$secure = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on';

session_set_cookie_params([
    'lifetime' => 0,
    'path' => '/',
    'secure' => $secure,
    'httponly' => true,
    'samesite' => 'Lax' // Lax works for most cases; use 'None' only if cross-site cookies are needed
]);

session_start();

// Allowed frontend origins
$allowedOrigins = [
    "http://localhost:5173",
    "https://aripen-frontend.vercel.app"
];

$origin = $_SERVER['HTTP_ORIGIN'] ?? '';
if (in_array($origin, $allowedOrigins)) {
    header("Access-Control-Allow-Origin: $origin");
    header("Access-Control-Allow-Credentials: true");
}

header("Access-Control-Expose-Headers: Set-Cookie");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Content-Type: application/json");

// Preflight OPTIONS request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

require_once __DIR__ . '/../../Config/Db.php';

// Get JSON input
$data = json_decode(file_get_contents("php://input"), true);

if (!$data || !isset($data['email'], $data['password'])) {
    echo json_encode([
        "loggedIn" => false,
        "session" => null,
        "UserData" => null,
        "status" => "no data received"
    ]);
    exit();
}

$email = strtolower(trim($data['email']));
$password = trim($data['password']);

// Find user in DB
$finduser = $db->User->findOne(['email' => $email]);

if ($finduser && password_verify($password, $finduser['password'])) {

    // Set session
    $_SESSION['user'] = [
        "eid" => $finduser['empId'],
        "role" => $finduser['role']
    ];

    // Remove password before sending response
    unset($finduser['password']);

    echo json_encode([
        "loggedIn" => true,
        "session" => $_SESSION['user'],
        "UserData" => $finduser,
        "status" => "success"
    ]);

} else {
    echo json_encode([
        "loggedIn" => false,
        "session" => null,
        "UserData" => null,
        "status" => "invalid credentials"
    ]);
}
?>