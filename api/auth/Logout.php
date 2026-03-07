<?php
$allowedOrigins = [
    "http://localhost:5173",
    "https://aripen-frontend.vercel.app"
];

// Handle CORS
if (isset($_SERVER['HTTP_ORIGIN']) && in_array($_SERVER['HTTP_ORIGIN'], $allowedOrigins)) {
    header("Access-Control-Allow-Origin: " . $_SERVER['HTTP_ORIGIN']);
    header("Access-Control-Allow-Credentials: true");
    header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type, Authorization");
}

// Handle preflight request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

header("Content-Type: application/json");

// Start session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Clear session
session_unset();
session_destroy();

// Return JSON response
echo json_encode([
    'status' => 'success',
    'message' => 'Logged out successfully'
]);
?>