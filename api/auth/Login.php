<?php

ini_set('session.cookie_samesite', 'None');
ini_set('session.cookie_secure', '1');
ini_set('session.save_path', sys_get_temp_dir());

session_set_cookie_params([
    'lifetime' => 0,
    'path' => '/',
    'secure' => true,
    'httponly' => true,
    'samesite' => 'None'
]);

session_start();

$allowedOrigins = [
    "http://localhost:5173",
    "https://aripen-frontend.vercel.app"
];

$origin = $_SERVER['HTTP_ORIGIN'] ?? '';

if (in_array($origin, $allowedOrigins)) {
    header("Access-Control-Allow-Origin: $origin");
}

header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Credentials: true");
header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

require_once __DIR__ . '/../../Config/Db.php';

$data = json_decode(file_get_contents("php://input"), true);

if (!$data) {
    echo json_encode(["status" => "no data received"]);
    exit();
}

$email = strtolower(trim($data['email']));
$password = trim($data['password']);

$finduser = $db->User->findOne(['email' => $email]);

if ($finduser && password_verify($password, $finduser['password'])) {

    $_SESSION['user'] = [
        "eid" => $finduser['empId'],
        "role" => $finduser['role']
    ];

    unset($finduser['password']);

    echo json_encode([
        "loggedIn" => true,
        "session" => $_SESSION['user'],
        "UserData" => $finduser
    ]);

} else {

    echo json_encode([
        "status" => "invalid credentials"
    ]);
}
?>