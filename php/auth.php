<?php
/**
 * User Authentication for TIF Service
 * Handles user registration, login, and session management
 */

require_once __DIR__ . '/db.php';

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Register a new user
 * @param string $name
 * @param string $email
 * @param string $password
 * @param string $phone
 * @param string $address
 * @return array ['success' => bool, 'message' => string, 'user_id' => int]
 */
function registerUser($name, $email, $password, $phone = '', $address = '') {
    // Validate input
    if (empty($name) || empty($email) || empty($password)) {
        return ['success' => false, 'message' => 'Name, email, and password are required.'];
    }
    
    // Check if email already exists
    $existingUser = fetchOne('users', 'email = :email', ['email' => $email]);
    if ($existingUser) {
        return ['success' => false, 'message' => 'Email already registered.'];
    }
    
    // Hash password
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
    
    // Insert user
    $data = [
        'name' => $name,
        'email' => $email,
        'password' => $hashedPassword,
        'phone' => $phone,
        'address' => $address
    ];
    
    $userId = insert('users', $data);
    
    if ($userId) {
        return ['success' => true, 'message' => 'Registration successful!', 'user_id' => $userId];
    }
    
    return ['success' => false, 'message' => 'Registration failed. Please try again.'];
}

/**
 * Login user
 * @param string $email
 * @param string $password
 * @return array ['success' => bool, 'message' => string, 'user' => array]
 */
function loginUser($email, $password) {
    // Validate input
    if (empty($email) || empty($password)) {
        return ['success' => false, 'message' => 'Email and password are required.'];
    }
    
    // Get user by email
    $user = fetchOne('users', 'email = :email', ['email' => $email]);
    
    if (!$user) {
        return ['success' => false, 'message' => 'Invalid email or password.'];
    }
    
    // Verify password
    if (!password_verify($password, $user['password'])) {
        return ['success' => false, 'message' => 'Invalid email or password.'];
    }
    
    // Set session
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['user_name'] = $user['name'];
    $_SESSION['user_email'] = $user['email'];
    
    return ['success' => true, 'message' => 'Login successful!', 'user' => $user];
}

/**
 * Logout user
 */
function logoutUser() {
    // Unset all session variables
    $_SESSION = [];
    
    // Destroy session
    if (session_status() === PHP_SESSION_ACTIVE) {
        session_destroy();
    }
    
    return ['success' => true, 'message' => 'Logged out successfully.'];
}

/**
 * Check if user is logged in
 * @return bool
 */
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

/**
 * Get current user
 * @return array|null
 */
function getCurrentUser() {
    if (!isLoggedIn()) {
        return null;
    }
    
    return fetchOne('users', 'id = :id', ['id' => $_SESSION['user_id']]);
}

/**
 * Update user profile
 * @param int $userId
 * @param array $data
 * @return array
 */
function updateUserProfile($userId, $data) {
    // Remove password from data if empty
    if (isset($data['password']) && empty($data['password'])) {
        unset($data['password']);
    } elseif (isset($data['password'])) {
        $data['password'] = password_hash($data['password'], PASSWORD_BCRYPT);
    }
    
    $result = update('users', $data, 'id = :id', ['id' => $userId]);
    
    if ($result) {
        return ['success' => true, 'message' => 'Profile updated successfully.'];
    }
    
    return ['success' => false, 'message' => 'Failed to update profile.'];
}

// Handle AJAX requests
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    header('Content-Type: application/json');
    
    $response = ['success' => false, 'message' => 'Invalid action.'];
    
    switch ($_POST['action']) {
        case 'register':
            $result = registerUser(
                $_POST['name'] ?? '',
                $_POST['email'] ?? '',
                $_POST['password'] ?? '',
                $_POST['phone'] ?? '',
                $_POST['address'] ?? ''
            );
            $response = $result;
            break;
            
        case 'login':
            $result = loginUser($_POST['email'] ?? '', $_POST['password'] ?? '');
            $response = $result;
            break;
            
        case 'logout':
            $response = logoutUser();
            break;
            
        case 'update_profile':
            if (!isLoggedIn()) {
                $response = ['success' => false, 'message' => 'Please login first.'];
            } else {
                $data = [
                    'name' => $_POST['name'] ?? '',
                    'phone' => $_POST['phone'] ?? '',
                    'address' => $_POST['address'] ?? ''
                ];
                if (!empty($_POST['password'])) {
                    $data['password'] = $_POST['password'];
                }
                $response = updateUserProfile($_SESSION['user_id'], $data);
            }
            break;
    }
    
    echo json_encode($response);
    exit;
}
