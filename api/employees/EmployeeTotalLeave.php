<?php 

require_once __DIR__ . '/../../Config/Db.php';


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
if($data){
 $requests = $Leave_collection->find([
        'empId' => $data['empId']
    ]);

    $leaveArray = iterator_to_array($requests);

    if ($leaveArray) {
        echo json_encode([
            'leaves' => $leaveArray
        ]);
    } else {
        echo json_encode([
        'leaves' => []
    ]);
    }

}

?>