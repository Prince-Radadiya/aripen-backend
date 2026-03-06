<?php 
$allowedOrigins = [
    "http://localhost:5173",
    "https://aripen-frontend.vercel.app"
];

if (isset($_SERVER['HTTP_ORIGIN']) && in_array($_SERVER['HTTP_ORIGIN'], $allowedOrigins)) {
    header("Access-Control-Allow-Origin: " . $_SERVER['HTTP_ORIGIN']);
}

header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Methods: GET");
header("Content-Type: application/json");

require_once __DIR__ . '/../../Config/Db.php';

$project_collection = $db->Project;



if ($_SERVER['REQUEST_METHOD'] === 'GET') {

    // Fetch only projectId field from all documents
    $cursor = $project_collection->find(
        [],
        ['projection' => ['_id' => 0, 'projectId' => 1]]
    );

    $projectIds = [];

    foreach ($cursor as $doc) {
        if (isset($doc['projectId'])) {
            $projectIds[] = $doc['projectId'];
        }
    }

    echo json_encode([
        "status" => "success",
        "projects" => $projectIds
    ]);
}
?>
