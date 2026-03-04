<?php
// Database Setup Script - Run this once to create the database

$host = "localhost";
$user = "root";
$password = "";

$conn = new mysqli($host, $user, $password);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Read and execute SQL file
$sql = file_get_contents(__DIR__ . '/../sql/railway_management_system.sql');

if ($conn->multi_query($sql)) {
    echo "<!DOCTYPE html>
    <html lang='en'>
    <head>
        <meta charset='UTF-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        <title>Database Setup - Railway Management</title>
        <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css' rel='stylesheet'>
        <link href='styles.css' rel='stylesheet'>
        <style>
            body {
                background: linear-gradient(135deg, #1e3a8a 0%, #0f172a 100%);
                min-height: 100vh;
                display: flex;
                align-items: center;
                justify-content: center;
                font-family: 'Inter', sans-serif;
            }
            .card {
                background: rgba(30, 41, 59, 0.9);
                border: 1px solid rgba(255,255,255,0.1);
                border-radius: 20px;
                padding: 3rem;
                text-align: center;
                color: white;
                max-width: 500px;
            }
            .success-icon {
                font-size: 4rem;
                margin-bottom: 1rem;
            }
            h1 { color: #10b981; margin-bottom: 1rem; }
            p { color: #94a3b8; margin-bottom: 2rem; }
            .btn {
                padding: 12px 28px;
                border-radius: 12px;
                font-weight: 600;
                text-decoration: none;
                display: inline-block;
            }
            .btn-primary {
                background: linear-gradient(135deg, #3b82f6, #1e3a8a);
                color: white;
            }
        </style>
    </head>
    <body>
        <div class='card'>
            <div class='success-icon'>✅</div>
            <h1>Database Setup Complete!</h1>
            <p>The railway_management database has been created successfully with all tables and sample data.</p>
            <a href='home.php' class='btn btn-primary'>Go to Home Page</a>
        </div>
    </body>
    </html>";
} else {
    echo "Error setting up database: " . $conn->error;
}

$conn->close();
?>

