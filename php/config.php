<?php
/**
 * Database Configuration for TIF Service
 * 
 * Update these values according to your local/server database setup
 */

// Database credentials
define('DB_HOST', 'localhost');
define('DB_NAME', 'tif_service');
define('DB_USER', 'root');
define('DB_PASS', ''); // Default XAMPP/WAMP has empty password
define('DB_CHARSET', 'utf8mb4');

/**
 * Get PDO database connection
 * @return PDO
 */
function getDBConnection() {
    $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ];
    
    try {
        $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
        return $pdo;
    } catch (PDOException $e) {
        error_log("Database Connection Error: " . $e->getMessage());
        throw new Exception("Database connection failed. Please try again later.");
    }
}

/**
 * Get mysqli database connection (alternative)
 * @return mysqli
 */
function getDBConnectionmysqli() {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    
    if ($conn->connect_error) {
        error_log("Database Connection Error: " . $conn->connect_error);
        die("Database connection failed. Please try again later.");
    }
    
    $conn->set_charset(DB_CHARSET);
    return $conn;
}
