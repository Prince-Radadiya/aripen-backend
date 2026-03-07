<?php

$allowedOrigins = [
    "http://localhost:5173",
    "https://aripen-frontend.vercel.app"
];

if (isset($_SERVER['HTTP_ORIGIN']) && in_array($_SERVER['HTTP_ORIGIN'], $allowedOrigins)) {
    header("Access-Control-Allow-Origin: " . $_SERVER['HTTP_ORIGIN']);
}

header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Credentials: true");
header("Content-Type: application/json");

require_once __DIR__ . '/../../Config/Db.php';




if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

session_start();

$data = json_decode(file_get_contents("php://input"), true);



if (!$data) {
    echo json_encode([
        "status" => "no data received"
    ]);
    exit();
}

$email = strtolower(trim($data['email']));
$password = trim($data['password']);

try {

    // case insensitive email search
   $finduser = $db->User->findOne([
    'email' => $email
]);
    if ($finduser) {

        if (password_verify($password, $finduser['password'])) {

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
                "status" => "wrong password"
            ]);

        }

    } else {

        echo json_encode([
            "status" => "user not found"
        ]);

    }

} catch (Exception $e) {

    echo json_encode([
        "status" => "error",
        "message" => $e->getMessage()
    ]);

}