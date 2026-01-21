CREATE DATABASE tiffin_db;
USE tiffin_db;

CREATE TABLE customer (
    cust_id INT PRIMARY KEY AUTO_INCREMENT,
    cust_name VARCHAR(100),
    cust_email VARCHAR(100),
    cust_mobile VARCHAR(15),
    password VARCHAR(100),
    address TEXT
);

CREATE TABLE restaurant (
    res_id INT PRIMARY KEY AUTO_INCREMENT,
    res_name VARCHAR(100),
    address TEXT,
    mobile VARCHAR(15)
);

CREATE TABLE item (
    item_id INT PRIMARY KEY AUTO_INCREMENT,
    item_name VARCHAR(100),
    price DOUBLE,
    description TEXT,
    res_id INT,
    FOREIGN KEY (res_id) REFERENCES restaurant(res_id)
);

CREATE TABLE delivery_person (
    dp_id INT PRIMARY KEY AUTO_INCREMENT,
    dp_name VARCHAR(100),
    dp_mobile VARCHAR(15)
);

CREATE TABLE orders (
    order_id INT PRIMARY KEY AUTO_INCREMENT,
    order_date DATETIME,
    cust_id INT,
    res_id INT,
    dp_id INT,
    total_amount DOUBLE,
    status VARCHAR(50),
    FOREIGN KEY (cust_id) REFERENCES customer(cust_id),
    FOREIGN KEY (res_id) REFERENCES restaurant(res_id),
    FOREIGN KEY (dp_id) REFERENCES delivery_person(dp_id)
);

CREATE TABLE payment (
    pay_id INT PRIMARY KEY AUTO_INCREMENT,
