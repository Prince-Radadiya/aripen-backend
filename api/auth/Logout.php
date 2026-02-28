<?php 

session_start();

// header("Access-Control-Allow-Origin: http://localhost:5173");
header("Access-Control-Allow-Credentials: true");
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: https://aripen-frontend.vercel.app");

$session=[];
session_unset();  
session_destroy();  

echo json_encode([
     'status' => 'success',
    'message' => 'Logged out successfully'
])

?>