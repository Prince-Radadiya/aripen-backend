<?php 

require_once __DIR__ . '/../../Config/Db.php';


if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$project_collection=$db->Project;

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

if($data){

    $projectId = strtoupper(trim($data['projectId']));

    $project = $project_collection->findOne(['projectId' => $projectId,]);

    if (!$project) {
        echo json_encode(["status" => "error", "message" => "Project not found"]);
        exit;
    }

     $result = $project_collection->deleteOne(['projectId' => $projectId]);

     if ($result->getDeletedCount() > 0) {
        echo json_encode(["status" => "success", "message" => "Project removed"]);
     } else {
        echo json_encode(["status" => "error", "message" => "Failed to remove project"]);
        }

}
?>