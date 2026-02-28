<?php
session_start();

// header("Access-Control-Allow-Origin: http://localhost:5173");
header("Access-Control-Allow-Credentials: true");
header("Content-Type: application/json");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods:GET, POST, OPTIONS");
header("Access-Control-Allow-Origin: https://aripen-frontend.vercel.app");

if(isset($_SESSION['user'])) {
    echo json_encode([
        'loggedIn' => true,
        'user' => $_SESSION['user']
    ]);
} else{
    echo json_encode([
        'loggedIn' => false
    ]);
}