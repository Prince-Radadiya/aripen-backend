<?php 

require_once __DIR__ . '/../../Config/Db.php';

$user_collection = $db->User;

header("Access-Control-Allow-Origin: http://localhost:5173");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Methods: GET");
header("Content-Type: application/json");


if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {

    $employeesId = $user_collection->distinct('empId', ['role' => 'Employee']);
  
    if (empty($employeesId)) {
        echo json_encode([
            "status" => "error",
            "message" => "No employees found"
        ]);
        exit;
    }   
    echo json_encode([
        "status" => "success",
        "employees" => $employeesId
    ]);
}

?>