<?php 

require_once __DIR__ . '/../../Config/Db.php';


if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$salary_collection =$db->Salary;

header("Access-Control-Allow-Origin:  http://localhost:5173");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Content-Type: application/json");
header("Access-Control-Allow-Credentials: true");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit(); 
}

$salary =$salary_collection->find();

$salaryArrayy = iterator_to_array($salary, false);
if ($salaryArrayy) {
  echo json_encode($salaryArrayy);
}

?>