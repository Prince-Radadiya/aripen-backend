<?php 

require_once __DIR__ . '/../../Config/Db.php';


if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$Expense_collection =$db->Expense;

header("Access-Control-Allow-Origin:  http://localhost:5173");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Content-Type: application/json");
header("Access-Control-Allow-Credentials: true");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit(); 
}

$Expense =$Expense_collection->find();

$ExpenseArrayy = iterator_to_array($Expense, false);
if ($ExpenseArrayy) {
  echo json_encode($ExpenseArrayy);
}

?>