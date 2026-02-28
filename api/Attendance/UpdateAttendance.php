<?php 

require_once __DIR__ . '/../../Config/Db.php';


if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
date_default_timezone_set('Asia/Kolkata');
$Attendance_collection=$db->Attendance;

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

$employeeId = $data['empid'] ?? null;
$status = $data['status'] ?? null;
$totalhours = $data['totalHours'] ?? null;
$date = $data['date'] ?? null;

if ($data) {
    $Attendance_collection->insertOne([
        "employeeId" => $employeeId,
        "date" => $date,
        "status" => $status,
        "punchIn" => null,
        "punchOut" => null,
        "totalHours" => $totalhours,
        "Data" => "Admin Added",
        "breaks" => [],
        "calculatedStatus" => $status
    ]);

    echo json_encode(["status" => "success", "message" => "Attendance updated successfully", "employeeId" => $employeeId]);

}
?>