<?php 

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../../Config/Db.php';

// $project_collection = $db->Project;
$task_collection = $db->Tasks;
// $user_collection =$db->User;

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}



header("Access-Control-Allow-Origin: http://localhost:5173");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Content-Type: application/json");
header("Access-Control-Allow-Credentials: true");

if($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit(); 
}

$data = json_decode(file_get_contents("php://input"),true);

if ($data) {
$taskid = $data['Id'];
$action =$data['Action'];

switch ($action) {
    case 'in-progress':
            $updateResult= $task_collection->updateOne(
            ["taskId"=>$taskid],
           [ '$set'=>['status'=>"in-progress"]]
            );
            echo "Matched: " . $updateResult->getMatchedCount() . "\n";
            echo "Modified: " . $updateResult->getModifiedCount();
        break;
    
    case 'completed':
        $updateResult= $task_collection->updateOne(
            ["taskId"=>$taskid],
           [ '$set'=>['status'=>"completed"]]
        );
        echo "Matched: " . $updateResult->getMatchedCount() . "\n";
        echo "Modified: " . $updateResult->getModifiedCount();
        break;
    
    default:
       echo json_encode(["result"=>"invalid case"]);
        break;
}

}else{
    echo json_encode(["error"=>"no data"]);
}
?>