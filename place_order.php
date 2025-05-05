<?php
session_start();
include "db_connection.php"; 


if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    echo "Your cart is empty.";
    exit;
}

$cart = $_SESSION['cart'];
$total_price = 0;
$user_id = 1; 


$conn->begin_transaction();

try {
    
    foreach ($cart as $product_id => $item) {
        $stmt = $conn->prepare("SELECT stock FROM product WHERE id = ?");
        $stmt->bind_param("i", $product_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $product = $result->fetch_assoc();

        if ($product && $item['quantity'] > $product['stock']) {
            throw new Exception("❌ Not enough stock for {$item['title']}. Only {$product['stock']} available.");
        }
    }

   
    $stmt = $conn->prepare("INSERT INTO orders (user_id, total_price, status, created_at) VALUES (?, 0, 'pending', NOW())");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $order_id = $stmt->insert_id;

    foreach ($cart as $product_id => $item) {
        $quantity = $item['quantity'];
        $unit_price = $item['price'];
        $total = $unit_price * $quantity;

   
        $stmt = $conn->prepare("INSERT INTO order_items (order_id, book_id, quantity, unit_price, total_price) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("iiidd", $order_id, $product_id, $quantity, $unit_price, $total);
        $stmt->execute();


        $stmt = $conn->prepare("UPDATE product SET stock = stock - ? WHERE id = ?");
        $stmt->bind_param("ii", $quantity, $product_id);
        $stmt->execute();

        if ($stmt->affected_rows === 0) {
            throw new Exception("❌ Failed to update stock for product ID: $product_id.");
        }

        $total_price += $total;
    }

    $stmt = $conn->prepare("UPDATE orders SET total_price = ? WHERE id = ?");
    $stmt->bind_param("di", $total_price, $order_id);
    $stmt->execute();

  
    unset($_SESSION['cart']);

    
    $conn->commit();
    echo "🎉 Order placed successfully! Order ID: $order_id";
} catch (Exception $e) {
 
    $conn->rollback();
    echo $e->getMessage();
}
?>
