<?php

session_set_cookie_params([
    'lifetime'=>0,
    'path'=>'/',
    'domain'=>'aripen-backend.onrender.com',
    'secure'=>true,
    'httponly'=>true,
    'samesite'=>'None'
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

header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Credentials: true");
header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

require_once __DIR__ . '/../../Config/Db.php';


if (isset($_SESSION['user'])) {

    $empid = $_SESSION['user']['eid'];

    $findEmpuser = $db->User->findOne([
        'empId' => $empid
    ]);

    if ($findEmpuser) {

        $findEmpuser['_id'] = (string)$findEmpuser['_id'];

        echo json_encode([
            "status" => "success",
            "UserData" => $findEmpuser
        ]);

    } else {

        echo json_encode([
            "status" => "employee not found"
        ]);
    }

} else {

    echo json_encode([
        "status" => "session expired"
    ]);
}