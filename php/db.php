<?php
/**
 * TIF Service Database Connection & Helper Functions
 * Include this file in any PHP file that needs database access
 * 
 * Usage:
 *   include 'php/db.php';
 *   $pdo = getDBConnection();
 */

// Include configuration
require_once __DIR__ . '/config.php';

/**
 * Initialize database connection
 * @return PDO
 */
function initDB() {
    return getDBConnection();
}

/**
 * Fetch all rows from a table
 * @param string $table
 * @param string $condition
 * @param array $params
 * @return array
 */
function fetchAll($table, $condition = '', $params = []) {
    $pdo = getDBConnection();
    $sql = "SELECT * FROM $table";
    
    if (!empty($condition)) {
        // Convert :param to SQLite format
        $condition = str_replace(':', '', $condition);
        $sql .= " WHERE $condition";
    }
    
    $stmt = $pdo->prepare($sql);
    
    // Convert named params to positional for SQLite
    $values = array_values($params);
    $stmt->execute($values);
    return $stmt->fetchAll();
}

/**
 * Fetch single row from a table
 * @param string $table
 * @param string $condition
 * @param array $params
 * @return array|null
 */
function fetchOne($table, $condition, $params = []) {
    $pdo = getDBConnection();
    $condition = str_replace(':', '', $condition);
    $sql = "SELECT * FROM $table WHERE $condition LIMIT 1";
    
    $stmt = $pdo->prepare($sql);
    $values = array_values($params);
    $stmt->execute($values);
    return $stmt->fetch();
}

/**
 * Insert a record into a table
 * @param string $table
 * @param array $data
 * @return int|false
 */
function insert($table, $data) {
    $pdo = getDBConnection();
    $columns = implode(', ', array_keys($data));
    $placeholders = implode(', ', array_fill(0, count($data), '?'));
    
    $sql = "INSERT INTO $table ($columns) VALUES ($placeholders)";
    
    try {
        $stmt = $pdo->prepare($sql);
        $stmt->execute(array_values($data));
        return $pdo->lastInsertId();
    } catch (PDOException $e) {
        error_log("Insert Error: " . $e->getMessage());
        return false;
    }
}

/**
 * Update a record in a table
 * @param string $table
 * @param array $data
 * @param string $condition
 * @param array $params
 * @return bool
 */
function update($table, $data, $condition, $params = []) {
    $pdo = getDBConnection();
    $set = [];
    foreach (array_keys($data) as $key) {
        $set[] = "$key = :$key";
    }
    $setClause = implode(', ', $set);
    
    $condition = str_replace(':', '', $condition);
    $sql = "UPDATE $table SET $setClause WHERE $condition";
    
    $mergedParams = array_merge(array_values($data), array_values($params));
    
    try {
        $stmt = $pdo->prepare($sql);
        return $stmt->execute($mergedParams);
    } catch (PDOException $e) {
        error_log("Update Error: " . $e->getMessage());
        return false;
    }
}

/**
 * Delete a record from a table
 * @param string $table
 * @param string $condition
 * @param array $params
 * @return bool
 */
function delete($table, $condition, $params = []) {
    $pdo = getDBConnection();
    $condition = str_replace(':', '', $condition);
    $sql = "DELETE FROM $table WHERE $condition";
    
    try {
        $stmt = $pdo->prepare($sql);
        return $stmt->execute(array_values($params));
    } catch (PDOException $e) {
        error_log("Delete Error: " . $e->getMessage());
        return false;
    }
}

/**
 * Execute a custom query
 * @param string $sql
 * @param array $params
 * @return array|bool
 */
function query($sql, $params = []) {
    $pdo = getDBConnection();
    
    try {
        $stmt = $pdo->prepare($sql);
        $stmt->execute(array_values($params));
        
        // Return true for INSERT/UPDATE/DELETE, data for SELECT
        if (stripos(trim($sql), 'SELECT') === 0) {
            return $stmt->fetchAll();
        }
        return true;
    } catch (PDOException $e) {
        error_log("Query Error: " . $e->getMessage());
        return false;
    }
}
