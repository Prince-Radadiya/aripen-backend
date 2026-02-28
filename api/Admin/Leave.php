<?php 

require_once __DIR__ . '/../../Config/Db.php';

ini_set('display_errors', 0);        // ❌ Don't show errors in output
ini_set('log_errors', 1);            // ✅ Log errors instead
error_reporting(E_ALL);              // ✅ Report all errors

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$Leave_collection=$db->Leave;

header("Access-Control-Allow-Origin: http://localhost:5173");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Content-Type: application/json");




if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit(); 
}

$data = json_decode(file_get_contents("php://input"),true);


if ($data) {
    $leaveid =$data['Id'];
    $action =$data['Action'];

    file_put_contents("php://stderr", "ACTION RECEIVED: [$action]\n");

    switch ($action) {
        case 'approved':

            $updateResult= $Leave_collection->updateOne(
            ["leaveId"=>$leaveid],
            ['$set'=>['status'=>"approved"]]
            );

            echo json_encode([
                // "matched" => $updateResult->getMatchedCount(),
                // "modified" => $updateResult->getModifiedCount(), 
                "status" => "approved"
            ]);
            exit(); 
        // break;
        default:
          echo json_encode(["result"=>"invalid case"]);
           break;
}

} 
?>