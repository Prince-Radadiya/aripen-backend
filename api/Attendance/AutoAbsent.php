<?php
require_once __DIR__ . '/../../Config/Db.php';


if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
date_default_timezone_set('Asia/Kolkata');


header("Access-Control-Allow-Origin: http://localhost:5173");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Content-Type: application/json");


if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit(); 
}

// your attendance collection
$employeesCollection = $db->employees;
$Attendance_collection=$db->Attendance;

$today = date("Y-m-d");

$employees = $employeesCollection->find();

foreach ($employees as $employee) {
    $empId = $employee['employeeId'];

    
    $record = $Attendance_collection->findOne([
        'employeeId' => $empId,
        'date' => $today
    ]);



    if (!$record) {
        $Attendance_collection->insertOne([
            "employeeId" => $empId,
            "date" => $today,
            "status" => "Absent",
            "punchIn" => null,
            "punchOut" => null,
            "totalHours" => null,
            "breaks" => []
        ]);
    }
}
   
?>