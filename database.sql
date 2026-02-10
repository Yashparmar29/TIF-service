-- Database: tif_service
-- Run this SQL in phpMyAdmin or MySQL to create the database and tables

-- Create Database
CREATE DATABASE IF NOT EXISTS tif_service;
USE tif_service;

-- Table: Categories (Breakfast, Lunch, Dinner)
CREATE TABLE IF NOT EXISTS categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    image VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table: Menu Items
CREATE TABLE IF NOT EXISTS menu_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    category_id INT,
    name VARCHAR(150) NOT NULL,
    description TEXT,
    price DECIMAL(10,2) NOT NULL,
    image VARCHAR(255),
    is_available BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id)
);

-- Table: Users (for login/registration)
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    phone VARCHAR(20),
    address TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table: Orders
CREATE TABLE IF NOT EXISTS orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    total_amount DECIMAL(10,2) NOT NULL,
    status ENUM('pending', 'confirmed', 'delivered', 'cancelled') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

-- Table: Order Items
CREATE TABLE IF NOT EXISTS order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT,
    menu_item_id INT,
    quantity INT NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(id),
    FOREIGN KEY (menu_item_id) REFERENCES menu_items(id)
);

-- Table: Site Settings
CREATE TABLE IF NOT EXISTS site_settings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    site_name VARCHAR(255) NOT NULL,
    site_description TEXT,
    contact_email VARCHAR(255),
    contact_phone VARCHAR(20),
    address TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Insert Sample Categories
INSERT INTO categories (name, description) VALUES
('Breakfast', 'Start your day with our delicious breakfast options'),
('Lunch', 'Midday meals for energy and satisfaction'),
('Dinner', 'Evening dining experience');

-- Insert Sample Menu Items
INSERT INTO menu_items (category_id, name, description, price) VALUES
(1, 'Classic Pancakes', 'Fluffy pancakes with maple syrup', 5.99),
(1, 'Eggs Benedict', 'Poached eggs with hollandaise sauce', 8.99),
(1, 'Breakfast Platter', 'Assorted breakfast items', 12.99),
(2, 'Grilled Chicken Salad', 'Fresh salad with grilled chicken', 9.99),
(2, 'Club Sandwich', 'Triple-decker sandwich with fries', 8.99),
(2, 'Pasta Primavera', 'Fresh vegetables with pasta', 10.99),
(3, 'Steak Dinner', 'Grilled steak with vegetables', 18.99),
(3, 'Fish and Chips', 'Crispy fish with fries', 14.99),
(3, 'Vegetable Curry', 'Authentic vegetable curry with rice', 12.99);

-- Insert Sample Site Settings
INSERT INTO site_settings (site_name, site_description, contact_email, contact_phone, address) VALUES
('TIF Service', 'Your go-to food delivery service', 'info@tifservice.com', '+1-234-567-8900', '123 Main St, City, State 12345');
