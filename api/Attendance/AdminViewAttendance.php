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



// $data = json_decode(file_get_contents("php://input"),true);
// $employeeId = $data['empId'] ?? null;


if (isset($_GET['empId'])) {
    $empId = $_GET['empId'];
    // use $empId to query DB

    if ($empId) {
        $Attendance_collection = $db->Attendance;
        $Leave_collection = $db->Leave;

        $leaveRecords = $Leave_collection->find(['empId' => $empId]);
        $leaveArray = iterator_to_array($leaveRecords);
        $leaveEvents = [];

        foreach ($leaveArray as $leave) {
            $leaveEvents[] = [
                "leaveDate" => $leave['leaveDate'],
                "status" => $leave['status'],
                "reason" => $leave['reason'],
            ];
        }

        $attendanceRecords = $Attendance_collection->find(['employeeId' => $empId]);
        $attendanceArray = iterator_to_array($attendanceRecords);

            $events = [];
            foreach ($attendanceArray as $record) {
            $events[] = [
                "title" => $record['employeeId'] . " - " . ucfirst($record['status']),
                "start" => $record['date'],
                "end" => $record['date'],
                "status" => strtolower($record['status']),
                "totalHours" => $record['totalHours'] ,
                "Twork" => $record['workingToday']?? null 
            ];  
        }


        echo json_encode(["events" => $events , "leave" => $leaveEvents]);
   

    }else {
    echo json_encode(["status" => "error", "message" => "data not found"]);
    }

} else {
    echo json_encode(["error" => "empId missing"]);
}






?>