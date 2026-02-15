<?php
/**
 * SQLite Database Configuration for TIF Service
 * This is an alternative to MySQL for local development without a database server
 */

// Database file path
define('DB_PATH', __DIR__ . '/../tif_service.db');

/**
 * Get SQLite PDO database connection
 * @return PDO
 */
function getDBConnection() {
    static $pdo = null;
    
    if ($pdo === null) {
        try {
            $pdo = new PDO('sqlite:' . DB_PATH);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            
            // Initialize database tables
            initDatabase($pdo);
        } catch (PDOException $e) {
            error_log("Database Connection Error: " . $e->getMessage());
            throw new Exception("Database connection failed: " . $e->getMessage());
        }
    }
    
    return $pdo;
}

/**
 * Initialize SQLite database tables
 * @param PDO $pdo
 */
function initDatabase($pdo) {
    $queries = [
        // Categories table
        "CREATE TABLE IF NOT EXISTS categories (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            name VARCHAR(100) NOT NULL,
            description TEXT,
            image VARCHAR(255),
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP
        )",
        
        // Menu Items table
        "CREATE TABLE IF NOT EXISTS menu_items (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            category_id INTEGER,
            name VARCHAR(150) NOT NULL,
            description TEXT,
            price REAL NOT NULL,
            image VARCHAR(255),
            is_available INTEGER DEFAULT 1,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (category_id) REFERENCES categories(id)
        )",
        
        // Users table
        "CREATE TABLE IF NOT EXISTS users (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            name VARCHAR(100) NOT NULL,
            email VARCHAR(150) NOT NULL UNIQUE,
            password VARCHAR(255) NOT NULL,
            phone VARCHAR(20),
            address TEXT,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP
        )",
        
        // Orders table
        "CREATE TABLE IF NOT EXISTS orders (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            user_id INTEGER,
            total_amount REAL NOT NULL,
            status VARCHAR(20) DEFAULT 'pending',
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (user_id) REFERENCES users(id)
        )",
        
        // Order Items table
        "CREATE TABLE IF NOT EXISTS order_items (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            order_id INTEGER,
            menu_item_id INTEGER,
            quantity INTEGER NOT NULL,
            price REAL NOT NULL,
            FOREIGN KEY (order_id) REFERENCES orders(id),
            FOREIGN KEY (menu_item_id) REFERENCES menu_items(id)
        )",
        
        // Site Settings table
        "CREATE TABLE IF NOT EXISTS site_settings (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            site_name VARCHAR(255) NOT NULL,
            site_description TEXT,
            contact_email VARCHAR(255),
            contact_phone VARCHAR(20),
            address TEXT,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
        )"
    ];
    
    foreach ($queries as $sql) {
        $pdo->exec($sql);
    }
    
    // Insert sample data if empty
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM categories");
    $result = $stmt->fetch();
    
    if ($result['count'] == 0) {
        // Insert sample categories
        $pdo->exec("INSERT INTO categories (name, description) VALUES ('Breakfast', 'Start your day with our delicious breakfast options')");
        $pdo->exec("INSERT INTO categories (name, description) VALUES ('Lunch', 'Midday meals for energy and satisfaction')");
        $pdo->exec("INSERT INTO categories (name, description) VALUES ('Dinner', 'Evening dining experience')");
        
        // Insert sample menu items (prices in INR)
        $pdo->exec("INSERT INTO menu_items (category_id, name, description, price) VALUES (1, 'Classic Pancakes', 'Fluffy pancakes with maple syrup', 250)");
        $pdo->exec("INSERT INTO menu_items (category_id, name, description, price) VALUES (1, 'Eggs Benedict', 'Poached eggs with hollandaise sauce', 350)");
        $pdo->exec("INSERT INTO menu_items (category_id, name, description, price) VALUES (1, 'Breakfast Platter', 'Assorted breakfast items', 450)");
        $pdo->exec("INSERT INTO menu_items (category_id, name, description, price) VALUES (2, 'Grilled Chicken Salad', 'Fresh salad with grilled chicken', 399)");
        $pdo->exec("INSERT INTO menu_items (category_id, name, description, price) VALUES (2, 'Club Sandwich', 'Triple-decker sandwich with fries', 299)");
        $pdo->exec("INSERT INTO menu_items (category_id, name, description, price) VALUES (2, 'Pasta Primavera', 'Fresh vegetables with pasta', 350)");
        $pdo->exec("INSERT INTO menu_items (category_id, name, description, price) VALUES (3, 'Steak Dinner', 'Grilled steak with vegetables', 899)");
        $pdo->exec("INSERT INTO menu_items (category_id, name, description, price) VALUES (3, 'Fish and Chips', 'Crispy fish with fries', 599)");
        $pdo->exec("INSERT INTO menu_items (category_id, name, description, price) VALUES (3, 'Vegetable Curry', 'Authentic vegetable curry with rice', 450)");
        
        // Insert sample site settings
        $pdo->exec("INSERT INTO site_settings (site_name, site_description, contact_email, contact_phone, address) VALUES ('TIF Service', 'Your go-to food delivery service', 'info@tifservice.com', '+1-234-567-8900', '123 Main St, City, State 12345')");
    }
}
