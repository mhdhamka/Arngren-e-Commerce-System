<?php
/**
 * Shopping Cart Operations
 * Handles all cart-related database operations
 */

include('db_carngren.php');

/**
 * Add item to cart
 */
function addToCart($userID, $productID, $productName, $orderQty) {
    global $conn;
    
    // Validate quantity
    if ($orderQty <= 0) {
        return array('success' => false, 'message' => 'Invalid quantity');
    }
    
    // Check if product exists and has enough stock
    $checkStmt = $conn->prepare("SELECT productQty FROM product WHERE productID = ?");
    $checkStmt->bind_param("i", $productID);
    $checkStmt->execute();
    $result = $checkStmt->get_result();
    
    if ($result->num_rows == 0) {
        return array('success' => false, 'message' => 'Product not found');
    }
    
    $row = $result->fetch_assoc();
    if ($row['productQty'] < $orderQty) {
        return array('success' => false, 'message' => 'Insufficient stock. Available: ' . $row['productQty']);
    }
    
    // Check if item already in cart
    $existStmt = $conn->prepare("SELECT cartID, orderQty FROM cart WHERE userID = ? AND productID = ?");
    $existStmt->bind_param("ii", $userID, $productID);
    $existStmt->execute();
    $existResult = $existStmt->get_result();
    
    if ($existResult->num_rows > 0) {
        // Update existing cart item
        $existRow = $existResult->fetch_assoc();
        $newQty = $existRow['orderQty'] + $orderQty;
        
        $updateStmt = $conn->prepare("UPDATE cart SET orderQty = ? WHERE cartID = ?");
        $updateStmt->bind_param("ii", $newQty, $existRow['cartID']);
        
        if ($updateStmt->execute()) {
            return array('success' => true, 'message' => 'Cart updated successfully');
        } else {
            return array('success' => false, 'message' => 'Error updating cart');
        }
    } else {
        // Add new cart item
        $insertStmt = $conn->prepare("INSERT INTO cart (userID, productID, productName, orderQty) VALUES (?, ?, ?, ?)");
        $insertStmt->bind_param("iisi", $userID, $productID, $productName, $orderQty);
        
        if ($insertStmt->execute()) {
            return array('success' => true, 'message' => 'Item added to cart');
        } else {
            return array('success' => false, 'message' => 'Error adding to cart');
        }
    }
}

/**
 * Get cart items for a user
 */
function getCartItems($userID) {
    global $conn;
    
    $stmt = $conn->prepare("
        SELECT c.cartID, c.productID, c.productName, c.orderQty, 
               p.productPrice, p.productIMG, (c.orderQty * p.productPrice) as subtotal
        FROM cart c
        JOIN product p ON c.productID = p.productID
        WHERE c.userID = ?
        ORDER BY c.cartID DESC
    ");
    $stmt->bind_param("i", $userID);
    $stmt->execute();
    
    return $stmt->get_result();
}

/**
 * Get cart total
 */
function getCartTotal($userID) {
    global $conn;
    
    $stmt = $conn->prepare("
        SELECT SUM(c.orderQty * p.productPrice) as total
        FROM cart c
        JOIN product p ON c.productID = p.productID
        WHERE c.userID = ?
    ");
    $stmt->bind_param("i", $userID);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    
    return $row['total'] ?: 0;
}

/**
 * Remove item from cart
 */
function removeFromCart($cartID, $userID) {
    global $conn;
    
    // Verify cart item belongs to user
    $verifyStmt = $conn->prepare("SELECT cartID FROM cart WHERE cartID = ? AND userID = ?");
    $verifyStmt->bind_param("ii", $cartID, $userID);
    $verifyStmt->execute();
    
    if ($verifyStmt->get_result()->num_rows == 0) {
        return array('success' => false, 'message' => 'Item not found in cart');
    }
    
    $deleteStmt = $conn->prepare("DELETE FROM cart WHERE cartID = ?");
    $deleteStmt->bind_param("i", $cartID);
    
    if ($deleteStmt->execute()) {
        return array('success' => true, 'message' => 'Item removed from cart');
    } else {
        return array('success' => false, 'message' => 'Error removing item');
    }
}

/**
 * Update cart item quantity
 */
function updateCartQuantity($cartID, $userID, $newQty) {
    global $conn;
    
    if ($newQty <= 0) {
        return removeFromCart($cartID, $userID);
    }
    
    // Verify cart item belongs to user
    $verifyStmt = $conn->prepare("SELECT productID FROM cart WHERE cartID = ? AND userID = ?");
    $verifyStmt->bind_param("ii", $cartID, $userID);
    $verifyStmt->execute();
    $verifyResult = $verifyStmt->get_result();
    
    if ($verifyResult->num_rows == 0) {
        return array('success' => false, 'message' => 'Item not found');
    }
    
    $updateStmt = $conn->prepare("UPDATE cart SET orderQty = ? WHERE cartID = ?");
    $updateStmt->bind_param("ii", $newQty, $cartID);
    
    if ($updateStmt->execute()) {
        return array('success' => true, 'message' => 'Quantity updated');
    } else {
        return array('success' => false, 'message' => 'Error updating quantity');
    }
}

/**
 * Clear cart for a user
 */
function clearCart($userID) {
    global $conn;
    
    $stmt = $conn->prepare("DELETE FROM cart WHERE userID = ?");
    $stmt->bind_param("i", $userID);
    
    if ($stmt->execute()) {
        return array('success' => true, 'message' => 'Cart cleared');
    } else {
        return array('success' => false, 'message' => 'Error clearing cart');
    }
}

/**
 * Get cart count
 */
function getCartCount($userID) {
    global $conn;
    
    $stmt = $conn->prepare("SELECT COUNT(*) as count FROM cart WHERE userID = ?");
    $stmt->bind_param("i", $userID);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    
    return $row['count'];
}

?>