<?php
/**
 * Test file to verify database connection and display menu
 */
require_once __DIR__ . '/db.php';

header('Content-Type: application/json');

try {
    $categories = fetchAll('categories');
    $menuItems = fetchAll('menu_items');
    
    echo json_encode([
        'success' => true,
        'categories' => $categories,
        'menu_items' => $menuItems,
        'count' => count($menuItems)
    ], JSON_PRETTY_PRINT);
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ], JSON_PRETTY_PRINT);
}
