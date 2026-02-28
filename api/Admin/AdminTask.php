<?php 

require_once __DIR__ . '/../../Config/Db.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$task_collection = $db->Tasks;

// header("Access-Control-Allow-Origin:  http://localhost:5173");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Content-Type: application/json");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Origin: https://aripen-frontend.vercel.app");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit(); 
}

// Fetch only last 8 tasks (latest first)
$tasks = $task_collection->find(
    [],
    [
        'sort' => ['_id' => -1],
        'limit' => 5
    ]
);

$tasksArray = iterator_to_array($tasks, false);

echo json_encode($tasksArray);
?>
