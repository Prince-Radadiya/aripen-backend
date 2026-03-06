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

$client_collection = $db->Clients;




if ($_SERVER['REQUEST_METHOD'] === 'GET') {

    // Fetch only projectId field from all documents
    $cursor = $client_collection->find(
        [],
        ['projection' => ['_id' => 0, 'clientId' => 1]]
    );

    $clientIds = [];

    foreach ($cursor as $doc) {
        if (isset($doc['clientId'])) {
            $clientIds[] = $doc['clientId'];
        }
    }

    echo json_encode([
        "status" => "success",
        "clients" => $clientIds
    ]);
}
?>
