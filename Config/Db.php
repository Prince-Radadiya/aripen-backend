<?php
require __DIR__ . '/../vendor/autoload.php'; 

// $mongoUri = getenv('MONGO_URI');   // Get from Render environment

$client = new MongoDB\Client("mongodb+srv://statusall08:prince123A@cluster0.cjm5jcg.mongodb.net/AriPen");

$db = $client->AriPen;  // Your database name
?>