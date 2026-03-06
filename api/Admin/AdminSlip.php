<?php 
$allowedOrigins = [
    "http://localhost:5173",
    "https://aripen-frontend.vercel.app"
];

if (isset($_SERVER['HTTP_ORIGIN']) && in_array($_SERVER['HTTP_ORIGIN'], $allowedOrigins)) {
    header("Access-Control-Allow-Origin: " . $_SERVER['HTTP_ORIGIN']);
}
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Content-Type: application/json");
header("Access-Control-Allow-Credentials: true");

require_once __DIR__ . '/../../Config/Db.php';

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

if (isset($_GET['empId'])) {
    $empId = $_GET['empId'];
    $employee = $db->User->findOne(['empId' => $empId]);

    if ($employee) {
        echo json_encode($employee);
    } else {
        echo json_encode(["error" => "Employee not found"]);
    }
} else {
    echo json_encode(["error" => "empId is required"]);
}
?>
