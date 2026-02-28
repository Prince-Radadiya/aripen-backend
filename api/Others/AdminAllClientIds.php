<?php 

require_once __DIR__ . '/../../Config/Db.php';

$client_collection = $db->Clients;

// header("Access-Control-Allow-Origin: http://localhost:5173");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Methods: GET");
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: https://aripen-frontend.vercel.app");

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
