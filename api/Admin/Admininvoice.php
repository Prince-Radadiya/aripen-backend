<?php 

require_once __DIR__ . '/../../Config/Db.php';


if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$Invoice_collection =$db->Invoice;

// header("Access-Control-Allow-Origin:  http://localhost:5173");/
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Content-Type: application/json");
header("Access-Control-Allow-Credentials: true");

header("Access-Control-Allow-Origin: https://aripen-frontend.vercel.app");



if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit(); 
}

$Invoice =$Invoice_collection->find();

$InvoiceArrayy = iterator_to_array($Invoice, false);
if ($InvoiceArrayy) {
  echo json_encode($InvoiceArrayy);
}

?>