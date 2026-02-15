<?php
/**
 * Menu API for TIF Service
 * Handles fetching menu items by category
 */

require_once __DIR__ . '/db.php';

/**
 * Get all categories
 * @return array
 */
function getCategories() {
    return fetchAll('categories');
}

/**
 * Get category by ID
 * @param int $id
 * @return array|null
 */
function getCategoryById($id) {
    return fetchOne('categories', 'id = :id', ['id' => $id]);
}

/**
 * Get all menu items or by category
 * @param int|null $categoryId
 * @return array
 */
function getMenuItems($categoryId = null) {
    if ($categoryId) {
        return fetchAll('menu_items', 'category_id = :category_id AND is_available = 1', ['category_id' => $categoryId]);
    }
    return fetchAll('menu_items', 'is_available = 1');
}

/**
 * Get menu item by ID
 * @param int $id
 * @return array|null
 */
function getMenuItemById($id) {
    return fetchOne('menu_items', 'id = :id', ['id' => $id]);
}

/**
 * Search menu items
 * @param string $search
 * @return array
 */
function searchMenuItems($search) {
    $pdo = getDBConnection();
    $sql = "SELECT * FROM menu_items 
            WHERE is_available = 1 
            AND (name LIKE :search OR description LIKE :search)
            ORDER BY name";
    
    $stmt = $pdo->prepare($sql);
    $searchParam = "%$search%";
    $stmt->bindValue(':search', $searchParam, PDO::PARAM_STR);
    $stmt->execute();
    
    return $stmt->fetchAll();
}

/**
 * Add new menu item (admin)
 * @param array $data
 * @return array
 */
