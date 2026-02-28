<?php
require_once __DIR__ . '/../../Config/Db.php';


if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
date_default_timezone_set('Asia/Kolkata');
$Attendance_collection=$db->Attendance;

// header("Access-Control-Allow-Origin: http://localhost:5173");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Content-Type: application/json");

header("Access-Control-Allow-Origin: https://aripen-frontend.vercel.app");


if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit(); 
}


$data = json_decode(file_get_contents("php://input"),true);
$employeeId = $data['empId'] ?? null;

if(!$employeeId){
    echo json_encode(["status" => "error", "message" => "Employee ID required"]);
    exit();
}else{
    
 $today = date('Y-m-d');

 $exist = $Attendance_collection->findOne([
     'employeeId' => $employeeId,
     'date' => $today
    ]);

    if ($exist) {
    echo json_encode(["status" => "error", "message" => "Already punched in today"]);
    exit;

    }else{
        $punchInTime = date("H:i:s");

        $Attendance_collection->insertOne([
            "employeeId" => $employeeId,
            "date" => $today,
            "punchIn" => $punchInTime,
            "punchOut" => null,
            "totalHours" => null,
            "breaks" => []
        ]);

        echo json_encode([
        "status" => "success",
        "message" => "Punch in successful",
        "punchIn" => $punchInTime
    ]);
}


}
 
 
 ?>