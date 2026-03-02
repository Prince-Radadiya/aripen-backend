<?php
require __DIR__ . '/../vendor/autoload.php'; 

$mongoUri = getenv('MONGO_URI');   // Get from Render environment

$client = new MongoDB\Client($mongoUri);

$db = $client->AriPen;  // Your database name
?>