<?php
/**
 * Database Connection File for XAMPP MySQL
 * 
 * XAMPP Default Settings:
 * - Server: localhost
 * - Port: 3306
 * - Username: root
 * - Password: (empty by default)
 * - Database: tif_service (to be created)
 */

// Database Configuration
define('DB_SERVER', 'localhost');
define('DB_PORT', '3306');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'tif_service');

// Create connection using mysqli
$conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME, DB_PORT);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Set charset to UTF-8 for proper character encoding
$conn->set_charset("utf8mb4");

// Optional: Display success message (remove in production)
echo "Database connected successfully!";
?>
