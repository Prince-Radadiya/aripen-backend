<?php 
$allowedOrigins = [
    "http://localhost:5173",
    "https://aripen-frontend.vercel.app"
];

if (isset($_SERVER['HTTP_ORIGIN']) && in_array($_SERVER['HTTP_ORIGIN'], $allowedOrigins)) {
    header("Access-Control-Allow-Origin: " . $_SERVER['HTTP_ORIGIN']);
}
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Content-Type: application/json");


require_once __DIR__ . '/../../Config/Db.php';


if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$Client_collection=$db->Clients;



if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

      

$data = json_decode(file_get_contents("php://input"),true);

if($data){

    $clientId = strtoupper(trim($data['clientId']));

    $client = $Client_collection->findOne(['clientId' => $clientId,]);

    if (!$client) {
        echo json_encode(["status" => "error", "message" => "Client not found"]);
        exit;
    }

     $result = $Client_collection->deleteOne(['clientId' => $clientId ]);

     if ($result->getDeletedCount() > 0) {
        echo json_encode(["status" => "success", "message" => "Client removed"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Failed to remove client"]);
    }

}
?>