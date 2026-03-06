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

$Client_collection = $db->Clients;





if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

$data = json_decode(file_get_contents("php://input"), true);

if ($data) {

    // 🟡 STEP 1: Find the last client (highest clientId)
    $lastClient = $Client_collection->findOne([], ['sort' => ['clientId' => -1]]);

    if ($lastClient && isset($lastClient['clientId'])) {
        // Extract the numeric part from clientId, e.g. "CL005" → 5
        $num = (int)substr($lastClient['clientId'], 2);
        $num++;
        // Format new ID → "CL" + 3-digit padded number
        $newClientId = 'CL' . str_pad($num, 3, '0', STR_PAD_LEFT);
    } else {
        // If no clients exist yet, start with CL001
        $newClientId = 'CL001';
    }

    // 🟢 STEP 2: Create client data
    $useData = [
        'clientId' => $newClientId,
        'name' => $data['Name'],
        'email' => $data['email'],
        'contact' => $data['contact'],
        'address' => $data['address'],
        'createdAt' => $data['createdAt'],
        'status' => "active",
        'projects' => $data['projects'],
    ];

    // 🟢 STEP 3: Insert into MongoDB
    $result = $Client_collection->insertOne($useData);

    if ($result) {
        echo json_encode([
            "status" => 'success',
            "clientId" => $newClientId
        ]);
    } else {
        echo json_encode([
            "status" => 'failed'
        ]);
    }
} else {
    echo json_encode([ 
        "status" => "no data found"
    ]);
}

?>
