<?php 

require_once __DIR__ . '/../../Config/Db.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$project_collection = $db->Project;

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

$data = json_decode(file_get_contents("php://input"), true);

if ($data) {

    // ----------------------------------------
    // 1. Get LAST projectId from collection
    // ----------------------------------------
    $lastProject = $project_collection->findOne(
        [],
        ['sort' => ['projectId' => -1]]
    );

    if ($lastProject && isset($lastProject['projectId'])) {

        // Extract number (e.g. PR005 -> 5)
        $num = intval(substr($lastProject['projectId'], 2));

        // Increment number
        $newNumber = $num + 1;

        // Format new projectId (PR006)
        $newProjectId = "PR" . str_pad($newNumber, 3, "0", STR_PAD_LEFT);

    } else {
        // If no project exists — start with PR001
        $newProjectId = "PR001";
    }

    // ----------------------------------------
    // 2. Insert new project
    // ----------------------------------------
    $useData = [
        'projectId' => $newProjectId,
        'clientId' => $data['clientId'],
        'name' => $data['Name'],
        'description' => $data['description'],
        'assignedEmployees' => $data['assignedEmployees'],
        'status' => "ongoing",
        'startDate' => $data['startDate'],
        'deadline' => $data['deadline'],
    ];

    $result = $project_collection->insertOne($useData);

    if ($result) {
        echo json_encode([
            "status" => "success",
            "projectId" => $newProjectId
        ]);
    } else {
        echo json_encode([
            "status" => "failed"
        ]);
    }

} else {
    echo json_encode([
        "status" => "no data found"
    ]);
}

?>
