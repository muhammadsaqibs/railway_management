<?php
// Load .env variables manually
$env_path = __DIR__ . '/../.env';
if (file_exists($env_path)) {
    $env_vars = parse_ini_file($env_path);
    $host = $env_vars['DB_HOST'] ?? "localhost";
    $user = $env_vars['DB_USER'] ?? "root";
    $password = $env_vars['DB_PASS'] ?? "";
    $database = $env_vars['DB_NAME'] ?? "railway_management";
} else {
    // Fallback if .env is missing
    $host = "localhost";
    $user = "root";
    $password = "";
    $database = "railway_management";
}

$conn = new mysqli($host, $user, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
