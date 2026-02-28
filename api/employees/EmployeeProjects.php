<?php 

// header("Access-Control-Allow-Origin: http://localhost:5173");/
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Content-Type: application/json");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Origin: https://aripen-frontend.vercel.app");

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


require_once __DIR__ . '/../../Config/Db.php';

$project_collection = $db->Project;
$task_collection = $db->Tasks;
$user_collection =$db->User;

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


if($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit(); 
}


if(isset($_SESSION['user'])) {
    // Finding user
    $eid = $_SESSION['user']['eid'];
    $user = $user_collection->findOne(['empId'=>$eid]);

    
    // finding projecct
    $project_ids = $user['projectIds'];
    $project = $project_collection->find([
        "projectId"=>['$in'=>$project_ids]
    ]);
    $project_list = iterator_to_array($project, false);
    
  $tasks = [];
    if (!empty($project_ids)) {
        $task_cursor = $task_collection->find([
            "projectId" => ['$in' => $project_ids]
        ]);
        $tasks = iterator_to_array($task_cursor, false);
    }

  
        

   echo json_encode(["projects" => $project_list,"Tasks"=>$tasks]);



} else{
    echo json_encode([
        'user' => "not-found"
    ]);
}

?>



