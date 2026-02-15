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
function addMenuItem($data) {
    $required = ['category_id', 'name', 'price'];
    
    foreach ($required as $field) {
        if (empty($data[$field])) {
            return ['success' => false, 'message' => "$field is required."];
        }
    }
    
    $itemId = insert('menu_items', $data);
    
    if ($itemId) {
        return ['success' => true, 'message' => 'Menu item added.', 'item_id' => $itemId];
    }
    
    return ['success' => false, 'message' => 'Failed to add menu item.'];
}

/**
 * Update menu item (admin)
 * @param int $id
 * @param array $data
 * @return array
 */
function updateMenuItem($id, $data) {
    $result = update('menu_items', $data, 'id = :id', ['id' => $id]);
    
    if ($result) {
        return ['success' => true, 'message' => 'Menu item updated.'];
    }
    
    return ['success' => false, 'message' => 'Failed to update menu item.'];
}

/**
 * Delete menu item (admin)
 * @param int $id
 * @return array
 */
function deleteMenuItem($id) {
    $result = delete('menu_items', 'id = :id', ['id' => $id]);
    
    if ($result) {
        return ['success' => true, 'message' => 'Menu item deleted.'];
    }
    
    return ['success' => false, 'message' => 'Failed to delete menu item.'];
}

// Handle API requests
if ($_SERVER['REQUEST_METHOD'] === 'GET' || $_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');
    
    $action = $_GET['action'] ?? ($_POST['action'] ?? '');
    $response = ['success' => false, 'message' => 'Invalid action.'];
    
    switch ($action) {
        case 'get_categories':
            $response = [
                'success' => true,
                'categories' => getCategories()
            ];
            break;
            
        case 'get_category':
            $id = $_GET['id'] ?? ($_POST['id'] ?? 0);
            $category = getCategoryById($id);
            $response = [
                'success' => $category ? true : false,
                'category' => $category
            ];
            break;
            
        case 'get_menu_items':
            $categoryId = $_GET['category_id'] ?? null;
            $items = getMenuItems($categoryId);
            $response = [
                'success' => true,
                'menu_items' => $items
            ];
            break;
            
        case 'get_menu_item':
            $id = $_GET['id'] ?? 0;
            $item = getMenuItemById($id);
            $response = [
                'success' => $item ? true : false,
                'menu_item' => $item
            ];
            break;
            
        case 'search':
            $search = $_GET['q'] ?? '';
            $items = searchMenuItems($search);
            $response = [
                'success' => true,
                'menu_items' => $items
            ];
            break;
            
        case 'add_menu_item':
            $data = [
                'category_id' => $_POST['category_id'] ?? 0,
                'name' => $_POST['name'] ?? '',
                'description' => $_POST['description'] ?? '',
                'price' => $_POST['price'] ?? 0,
                'image' => $_POST['image'] ?? '',
                'is_available' => isset($_POST['is_available']) ? 1 : 0
            ];
            $response = addMenuItem($data);
            break;
            
        case 'update_menu_item':
            $id = $_POST['id'] ?? 0;
            $data = [];
            if (isset($_POST['name'])) $data['name'] = $_POST['name'];
            if (isset($_POST['description'])) $data['description'] = $_POST['description'];
            if (isset($_POST['price'])) $data['price'] = $_POST['price'];
            if (isset($_POST['image'])) $data['image'] = $_POST['image'];
            if (isset($_POST['is_available'])) $data['is_available'] = $_POST['is_available'];
            $response = updateMenuItem($id, $data);
            break;
            
        case 'delete_menu_item':
            $id = $_POST['id'] ?? 0;
            $response = deleteMenuItem($id);
            break;
    }
    
    echo json_encode($response);
    exit;
}
