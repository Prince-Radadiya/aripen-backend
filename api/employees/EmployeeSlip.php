
<?php 
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../../Config/Db.php';

$user_collection =$db->User;

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}



header("Access-Control-Allow-Origin: http://localhost:5173");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Content-Type: application/json");
header("Access-Control-Allow-Credentials: true");

if($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit(); 
}

$data = json_decode(file_get_contents("php://input"),true);




if ($data && isset($data['empId'])) {
    $empid = $data['empId'];
    $findEmpuser = $user_collection->findOne(['empId' => $empid]);

    if ($findEmpuser) {
        echo json_encode($findEmpuser);
    } else {
        echo json_encode(['error' => 'Employee not found']);
    }
} else {
    echo json_encode(['error' => 'Invalid or missing empId']);
}