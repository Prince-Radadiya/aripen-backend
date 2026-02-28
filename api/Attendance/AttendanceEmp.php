<?php
require_once __DIR__ . '/../../Config/Db.php';


if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
date_default_timezone_set('Asia/Kolkata');


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
$Attendance_collection=$db->Attendance;


if (isset($_SESSION['user'])) {
    $empId = $_SESSION['user']['eid'];

    $cursor = $Attendance_collection->find( 
        [
        'employeeId' => $empId,
        ],
        [
            'sort'  => ['date' => -1],   
            'limit' => 30
        ]
);

    // Convert cursor to array
    $records = iterator_to_array($cursor);

    if (!empty($records)) {
        echo json_encode(['status' => 'success', 'data' => array_values($records)]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'No attendance record found.']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'User not logged in.']);
}



?>