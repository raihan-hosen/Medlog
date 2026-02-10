<?php
// Database configuration
$host = getenv('DB_HOST') ?: 'localhost';
$user = getenv('DB_USER') ?: 'YOUR_DB_USERNAME';   // replace with your DB user locally
$pass = getenv('DB_PASS') ?: 'YOUR_DB_PASSWORD';   // replace with your DB password locally
$dbname = getenv('DB_NAME') ?: 'YOUR_DB_NAME';     // replace with your DB name locally

// Create connection
$db = new mysqli($host, $user, $pass, $dbname);

// Check connection
if ($db->connect_error) {
    die("Database connection failed: " . $db->connect_error);
}
?>
