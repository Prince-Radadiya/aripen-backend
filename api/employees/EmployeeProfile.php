<?php 

require_once __DIR__ . '/../../Config/Db.php';


if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


header("Access-Control-Allow-Origin:  http://localhost:5173");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Content-Type: application/json");
header("Access-Control-Allow-Credentials: true");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit(); 
}



if (isset($_SESSION['user'])){
    $empid = $_SESSION['user']['eid'];
    $findEmpuser = $db->User->findOne(['empId'=>$empid]);

     echo json_encode([
        "status"=>'success',
        "UserData"=>$findEmpuser
    ]);
    }else{
         echo json_encode([
        'status'=> " employee not found"
    ]);
}



?>
