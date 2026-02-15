<?php
/**
 * Orders API for TIF Service
 * Handles placing orders, viewing orders, and order management
 */

require_once __DIR__ . '/db.php';

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Place a new order
 * @param int $userId
 * @param array $items - Array of ['menu_item_id' => id, 'quantity' => qty]
 * @return array
 */
function placeOrder($userId, $items) {
    if (empty($items)) {
        return ['success' => false, 'message' => 'Cart is empty.'];
    }
    
    // Calculate total and validate items
    $pdo = getDBConnection();
    $totalAmount = 0;
    $orderItems = [];
    
    foreach ($items as $item) {
        $menuItem = fetchOne('menu_items', 'id = :id AND is_available = 1', ['id' => $item['menu_item_id']]);
        
        if (!$menuItem) {
            return ['success' => false, 'message' => 'One or more items are not available.'];
        }
        
        $subtotal = $menuItem['price'] * $item['quantity'];
        $totalAmount += $subtotal;
        
        $orderItems[] = [
            'menu_item_id' => $item['menu_item_id'],
            'quantity' => $item['quantity'],
            'price' => $menuItem['price']
        ];
    }
    
    // Create order
    $orderData = [
        'user_id' => $userId,
        'total_amount' => $totalAmount,
        'status' => 'pending'
    ];
    
    try {
        $pdo->beginTransaction();
        
        // Insert order
        $orderId = insert('orders', $orderData);
        
        if (!$orderId) {
            throw new Exception('Failed to create order.');
        }
        
        // Insert order items
        foreach ($orderItems as $item) {
            $orderItemData = [
                'order_id' => $orderId,
                'menu_item_id' => $item['menu_item_id'],
                'quantity' => $item['quantity'],
                'price' => $item['price']
            ];
            
            if (!insert('order_items', $orderItemData)) {
                throw new Exception('Failed to add order items.');
            }
        }
        
        $pdo->commit();
        
        return [
            'success' => true,
            'message' => 'Order placed successfully!',
            'order_id' => $orderId
        ];
        
    } catch (Exception $e) {
        $pdo->rollBack();
        error_log("Order Error: " . $e->getMessage());
        return ['success' => false, 'message' => 'Failed to place order.'];
    }
}

/**
 * Get user orders
 * @param int $userId
 * @return array
 */
function getUserOrders($userId) {
    $orders = fetchAll('orders', 'user_id = :user_id ORDER BY created_at DESC', ['user_id' => $userId]);
    
    foreach ($orders as &$order) {
        $order['items'] = getOrderItems($order['id']);
    }
    
    return $orders;
}

/**
 * Get order by ID
 * @param int $orderId
 * @return array|null
 */
function getOrderById($orderId) {
    $order = fetchOne('orders', 'id = :id', ['id' => $orderId]);
    
    if ($order) {
        $order['items'] = getOrderItems($orderId);
    }
    
    return $order;
}

/**
 * Get order items
 * @param int $orderId
 * @return array
 */
function getOrderItems($orderId) {
    $pdo = getDBConnection();
    $sql = "SELECT oi.*, mi.name, mi.description, mi.image 
            FROM order_items oi
            JOIN menu_items mi ON oi.menu_item_id = mi.id
            WHERE oi.order_id = :order_id";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['order_id' => $orderId]);
    
    return $stmt->fetchAll();
}

/**
 * Get all orders (admin)
 * @param string $status
 * @return array
 */
function getAllOrders($status = null) {
    if ($status) {
        $orders = fetchAll('orders', 'status = :status ORDER BY created_at DESC', ['status' => $status]);
    } else {
        $orders = fetchAll('orders', '1=1 ORDER BY created_at DESC');
    }
    
    foreach ($orders as &$order) {
        $order['items'] = getOrderItems($order['id']);
        $order['user'] = fetchOne('users', 'id = :id', ['id' => $order['user_id']]);
    }
    
    return $orders;
}

/**
 * Update order status (admin)
 * @param int $orderId
 * @param string $status
 * @return array
 */
function updateOrderStatus($orderId, $status) {
    $validStatuses = ['pending', 'confirmed', 'delivered', 'cancelled'];
    
    if (!in_array($status, $validStatuses)) {
        return ['success' => false, 'message' => 'Invalid status.'];
    }
    
    $result = update('orders', ['status' => $status], 'id = :id', ['id' => $orderId]);
    
    if ($result) {
        return ['success' => true, 'message' => 'Order status updated.'];
    }
    
    return ['success' => false, 'message' => 'Failed to update order status.'];
}

/**
 * Cancel order
 * @param int $orderId
 * @param int $userId
 * @return array
 */
function cancelOrder($orderId, $userId) {
    $order = fetchOne('orders', 'id = :id AND user_id = :user_id', ['id' => $orderId, 'user_id' => $userId]);
    
    if (!$order) {
        return ['success' => false, 'message' => 'Order not found.'];
    }
    
    if ($order['status'] !== 'pending') {
        return ['success' => false, 'message' => 'Cannot cancel this order.'];
    }
    
    return updateOrderStatus($orderId, 'cancelled');
}

// Handle API requests
if ($_SERVER['REQUEST_METHOD'] === 'GET' || $_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');
    
    $action = $_GET['action'] ?? ($_POST['action'] ?? '');
    $response = ['success' => false, 'message' => 'Invalid action.'];
    
    switch ($action) {
        case 'place_order':
            if (!isset($_SESSION['user_id'])) {
                $response = ['success' => false, 'message' => 'Please login first.'];
            } else {
                $items = json_decode($_POST['items'] ?? '[]', true);
                $response = placeOrder($_SESSION['user_id'], $items);
            }
            break;
            
        case 'get_user_orders':
            if (!isset($_SESSION['user_id'])) {
                $response = ['success' => false, 'message' => 'Please login first.'];
            } else {
                $orders = getUserOrders($_SESSION['user_id']);
                $response = [
                    'success' => true,
                    'orders' => $orders
                ];
            }
            break;
            
        case 'get_order':
            $orderId = $_GET['order_id'] ?? 0;
            $order = getOrderById($orderId);
            $response = [
                'success' => $order ? true : false,
                'order' => $order
            ];
            break;
            
        case 'get_all_orders':
            $status = $_GET['status'] ?? null;
            $orders = getAllOrders($status);
            $response = [
                'success' => true,
                'orders' => $orders
            ];
            break;
            
        case 'update_order_status':
            $orderId = $_POST['order_id'] ?? 0;
            $status = $_POST['status'] ?? '';
            $response = updateOrderStatus($orderId, $status);
            break;
            
        case 'cancel_order':
            if (!isset($_SESSION['user_id'])) {
                $response = ['success' => false, 'message' => 'Please login first.'];
            } else {
                $orderId = $_POST['order_id'] ?? 0;
                $response = cancelOrder($orderId, $_SESSION['user_id']);
            }
            break;
    }
    
    echo json_encode($response);
    exit;
}
