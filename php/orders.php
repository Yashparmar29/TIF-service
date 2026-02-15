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
