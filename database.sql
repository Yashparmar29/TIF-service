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
('Dinner', 'Evening dining experience'),
('Desserts', 'Sweet treats and desserts'),
('Snacks', 'Tasty snacks and quick bites'),
('Beverages', 'Refreshing drinks and beverages');

-- Insert Sample Menu Items (Matching HTML Pages)
-- Breakfast Category (category_id = 1)
INSERT INTO menu_items (category_id, name, description, price, image) VALUES
(1, 'Dhokla', 'Steamed fermented rice and chickpea flour snack', 4.99, 'https://images.unsplash.com/photo-1606491956689-2ea866880c84?w=400&h=300&fit=crop'),
(1, 'Thepla', 'Gujarati flatbread with fenugreek', 3.99, 'https://images.unsplash.com/photo-1601050690597-df0568f70950?w=400&h=300&fit=crop'),
(1, 'Handvo', 'Baked savory lentil and rice cake', 5.99, 'https://images.unsplash.com/photo-1585032226651-759b368d7246?w=400&h=300&fit=crop'),
(1, 'Poha', 'Flattened rice with onions and spices', 3.49, 'https://images.unsplash.com/photo-1601050690117-94f5f7fa4b2f?w=400&h=300&fit=crop'),
(1, 'Upma', 'Semolina cooked with vegetables', 3.99, 'https://images.unsplash.com/photo-1541519227354-08fa5d50c44d?w=400&h=300&fit=crop'),
(1, 'Misal Pav', 'Spiced sprout curry with bread', 5.99, 'https://images.unsplash.com/photo-1603894584373-5ac82b2ae398?w=400&h=300&fit=crop'),
(1, 'Mirchi Vada', 'Fried stuffed chili fritters', 4.49, 'https://images.unsplash.com/photo-1603048297172-c92544798d5e?w=400&h=300&fit=crop'),
(1, 'Mawa Kachori', 'Sweet fried pastry with khoya', 4.99, 'https://images.unsplash.com/photo-1608198138971-3ead0dc5a9d5?w=400&h=300&fit=crop'),
(1, 'Oats Bowl', 'Healthy oats with fruits and nuts', 4.49, 'https://images.unsplash.com/photo-1517673132405-a56a62b18caf?w=400&h=300&fit=crop'),
(1, 'Fruit Salad', 'Fresh seasonal fruits', 3.99, 'https://images.unsplash.com/photo-1540420773420-3366772f4999?w=400&h=300&fit=crop'),
(1, 'Grilled Vegetables', 'Mixed vegetables grilled to perfection', 5.49, 'https://images.unsplash.com/photo-1540420773420-3366772f4999?w=400&h=300&fit=crop'),
(1, 'Sandwich', 'Whole wheat sandwich with vegetables', 4.99, 'https://images.unsplash.com/photo-1528735602780-2552fd46c7af?w=400&h=300&fit=crop'),
(1, 'Fried Rice', 'Wok-tossed rice with vegetables', 5.99, 'https://images.unsplash.com/photo-1603133872878-684f208fb84b?w=400&h=300&fit=crop'),
(1, 'Noodles', 'Stir-fried noodles with sauce', 5.49, 'https://images.unsplash.com/photo-1569718212165-3a8278d5f624?w=400&h=300&fit=crop'),

-- Lunch Category (category_id = 2)
(2, 'Puran Poli', 'Sweet lentil stuffed flatbread', 6.99, 'https://images.unsplash.com/photo-1608198138971-3ead0dc5a9d5?w=400&h=300&fit=crop'),
(2, 'Missal Pav', 'Spicy sprout curry with bread', 5.99, 'https://images.unsplash.com/photo-1603133872878-684f208fb84b?w=400&h=300&fit=crop'),
(2, 'Pav Bhaji', 'Spiced vegetable mash with bread', 5.49, 'https://images.unsplash.com/photo-1603048297172-c92544798d5e?w=400&h=300&fit=crop'),
(2, 'Dal Baati Churma', 'Lentils with baked wheat balls', 7.99, 'https://images.unsplash.com/photo-1603894584373-5ac82b2ae398?w=400&h=300&fit=crop'),
(2, 'Gatte ki Sabzi', 'Gram flour dumplings in curry', 6.49, 'https://images.unsplash.com/photo-1585032226651-759b368d7246?w=400&h=300&fit=crop'),
(2, 'Ker Sangri', 'Desert beans and berries curry', 7.49, 'https://images.unsplash.com/photo-1606491956689-2ea866880c84?w=400&h=300&fit=crop'),
(2, 'Thepla', 'Gujarati flatbread with fenugreek', 3.99, 'https://images.unsplash.com/photo-1601050690597-df0568f70950?w=400&h=300&fit=crop'),
(2, 'Handvo', 'Baked savory lentil and rice cake', 5.99, 'https://images.unsplash.com/photo-1585032226651-759b368d7246?w=400&h=300&fit=crop'),

-- Dinner Category (category_id = 3)
(3, 'Kadhi', 'Yogurt based curry with pakoras', 6.99, 'https://images.unsplash.com/photo-1541519227354-08fa5d50c44d?w=400&h=300&fit=crop'),
(3, 'Rice', 'Basmati rice with tempering', 3.99, 'https://images.unsplash.com/photo-1586201375761-83865001e31c?w=400&h=300&fit=crop'),
(3, 'Dal', 'Yellow lentils with spices', 4.99, 'https://images.unsplash.com/photo-1547592180-85f173990554?w=400&h=300&fit=crop'),
(3, 'Varan Bhat', 'Dal with rice and ghee', 5.99, 'https://images.unsplash.com/photo-1547592180-85f173990554?w=400&h=300&fit=crop'),
(3, 'Bharli Vangi', 'Stuffed eggplant in coconut gravy', 7.49, 'https://images.unsplash.com/photo-1601050690597-df0568f70950?w=400&h=300&fit=crop'),
(3, 'Roti', 'Whole wheat flatbread', 2.99, 'https://images.unsplash.com/photo-1603048297172-c92544798d5e?w=400&h=300&fit=crop'),
(3, 'Dal Baati Churma', 'Lentils with baked wheat balls', 7.99, 'https://images.unsplash.com/photo-1603894584373-5ac82b2ae398?w=400&h=300&fit=crop'),
(3, 'Gatte ki Sabzi', 'Gram flour dumplings in curry', 6.49, 'https://images.unsplash.com/photo-1585032226651-759b368d7246?w=400&h=300&fit=crop'),
(3, 'Ker Sangri', 'Desert beans and berries curry', 7.49, 'https://images.unsplash.com/photo-1606491956689-2ea866880c84?w=400&h=300&fit=crop'),

-- Desserts Category (category_id = 4)
(4, 'Mohanthal', 'Rich besan-based sweet', 4.99, 'images/r1.jpg'),
(4, 'Gulab Jamun', 'Deep fried milk solids in syrup', 3.99, 'images/r2.jpg'),
(4, 'Badusha', 'Balushahi sweet pastry', 4.49, 'images/r3.jpg'),
(4, 'Rasgulla', 'Spongy cottage cheese balls', 3.99, 'images/n1.jpg'),
(4, 'Kaju Katli', 'Cashew fudge dessert', 5.99, 'images/n2.jpg'),
(4, 'Motichur Ladoo', 'Boondi laddu with ghee', 4.49, 'images/r1.jpg'),
(4, 'Mysore Pak', 'Besan and ghee sweet', 4.99, 'images/r2.jpg'),
(4, 'Payasam Mix', 'Rice pudding mix', 3.99, 'images/r3.jpg'),
(4, 'Halwa', 'Semolina sweet pudding', 3.49, 'images/n1.jpg'),

-- Snacks Category (category_id = 5)
(5, 'Pav Bhaji', 'Spiced vegetable mash with bread', 5.49, 'images/r1.jpg'),
(5, 'Dahi Puri', 'Crispy puris with yogurt and chutneys', 4.99, 'images/r2.jpg'),
(5, 'Bhel Puri', 'Puffed rice with vegetables', 4.49, 'images/r3.jpg'),
(5, 'Medu Vada', 'Fried lentil donuts', 4.99, 'images/n1.jpg'),
(5, 'Masala Dosa', 'Crispy rice crepe with potato filling', 6.99, 'images/n2.jpg'),
(5, 'Idli Sambar', 'Steamed rice cakes with lentil stew', 5.49, 'images/r1.jpg'),
(5, 'Samosa', 'Triangular fried pastry with filling', 3.99, 'images/r2.jpg'),
(5, 'Kachori', 'Fried pastry with spiced filling', 4.49, 'images/r3.jpg'),
(5, 'Aloo Tikki', 'Crispy potato patty', 4.99, 'images/n1.jpg'),
(5, 'Dhokla', 'Steamed fermented snack', 4.99, 'images/n2.jpg'),
(5, 'Thepla', 'Gujarati flatbread with fenugreek', 3.99, 'images/r1.jpg'),
(5, 'Khandvi', 'Rolled gram flour snack', 4.49, 'images/r3.jpg');

-- Insert Sample Site Settings
INSERT INTO site_settings (site_name, site_description, contact_email, contact_phone, address) VALUES
('TIF Service', 'Your go-to food delivery service', 'info@tifservice.com', '+1-234-567-8900', '123 Main St, City, State 12345');
