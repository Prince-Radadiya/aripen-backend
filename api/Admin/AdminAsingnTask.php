
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
$task_collection = $db->Tasks;

$last = $task_collection->findOne([], ['sort' => ['taskId' => -1]]);

if ($last && isset($last['taskId'])) {
    $num = (int)substr($last['taskId'], 3); 
    $num++;  
    $newId = 'TSK' . str_pad($num, 3, '0', STR_PAD_LEFT);  
} else {
    $newId = 'TSK001'; 
}

$data = json_decode(file_get_contents("php://input"),true);

if ($data) {;
$insertData = [
    'taskId' => $newId,
    'projectId' => $data['projectId'] ?? '', 
    'title' => $data['title'] ?? '',
    'description' => $data['description'] ?? '',
    'assignedTo' => $data['assignedTo'] ?? '',
    'dueDate' => $data['dueDate'] ?? '',
    'status' => $data['status'] ?? 'pending', // default status
    'createdAt' => date('Y-m-d H:i:s'),
];


    $result = $task_collection->insertOne($insertData);

    if ($result) {
         echo json_encode([
            "status"=>'success'
        ]);
    } else {
        echo json_encode([
            "status"=>'faild'
        ]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Invalid or missing data"]);
}

?>