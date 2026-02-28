<?php
require __DIR__ . '/../vendor/autoload.php';

use Dotenv\Dotenv;

// load .env from project root
$dotenv = Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();
?>