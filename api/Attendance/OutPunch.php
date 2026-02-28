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
$employeeId = $data['empId'] ?? null;
$workToday = $data['workingToday'] ?? null;

if ($employeeId) {

    $today =date('Y-m-d');

    $exist = $Attendance_collection->findOne([
     'employeeId' => $employeeId,
     'date' => $today
    ]);




    if ($exist) {

        if (!empty($exist['punchOut'])) {
            echo json_encode(["status" => "error", "message" => "Already punched out today"]);
            exit;
        } else {
            $punchOutTime = date("H:i:s");
            $punchInTime = $exist['punchIn'];
            $start = new DateTime($punchInTime);
            $end = new DateTime($punchOutTime);
            $interval = $start->diff($end);
            $totalHourss = $interval->format('%H:%I:%S');

                $inTime = new DateTime($punchInTime);
                $outTime = new DateTime($punchOutTime);

                $diff = $inTime->diff($outTime);
                $totalHours = ($diff->h) + ($diff->i / 60);

                if ($totalHours >= 8) {
                 $status = "Present";
                } elseif ($totalHours >= 3) {
                 $status = "HalfDay";
                } else {
                 $status = "Absent";
                }
 

            $Attendance_collection->updateOne(
            ["employeeId"=> $employeeId, "date" => $today],
            ['$set' => ["punchOut" => $punchOutTime,
            'totalHours' => $totalHourss,
            'status' => $status,
            'workingToday' => $workToday
            ]]
            );

            echo json_encode([
            "status" => "success",
            "message" => "Punch out successful",
            "punchOut" => $punchOutTime
            ]);
        } 
    }else {
        echo json_encode(["status" => "error", "message" => "Not punched in today"]);
    exit;
    }
}



?>