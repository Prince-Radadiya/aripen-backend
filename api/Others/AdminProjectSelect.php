<?php 

require_once __DIR__ . '/../../Config/Db.php';

$user_collection = $db->User;

// header("Access-Control-Allow-Origin: http://localhost:5173");/
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Methods: POST");
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: https://aripen-frontend.vercel.app");

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $data = json_decode(file_get_contents("php://input"), true);

    if (!isset($data['inputEmpid'])) {
        echo json_encode([
            "status" => "error",
            "message" => "Employee ID missing"
        ]);
        exit;
    }

    $emplId = $data['inputEmpid'];

    // Fetch only projectIds from the document
    $result = $user_collection->findOne(
        ['empId' => $emplId],
        ['projection' => ['_id' => 0, 'projectIds' => 1]]
    );

    if (!$result || !isset($result['projectIds'])) {
        echo json_encode([
            "status" => "error",
            "message" => "No projects found"
        ]);
        exit;
    }

    echo json_encode([
        "status" => "success",
        "projects" => $result['projectIds']
    ]);
}

?>
