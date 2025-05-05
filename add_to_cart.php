<?php
session_start();
include "db_connection.php";

if (!isset($_POST['product_id'])) {
    echo "❌ No product ID provided.";
    exit();
}

$product_id = $_POST['product_id'];
$quantity = $_POST['quantity'] ?? 1;


$stmt = $conn->prepare("SELECT title, price, stock, image_name FROM product WHERE id = ?");
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();
$product = $result->fetch_assoc();

if ($product) {
 
    if ($product['stock'] >= $quantity) {
        $item_total = $product['price'] * $quantity;

 
        $_SESSION['cart'][$product_id] = [
            'id' => $product_id,
            'title' => $product['title'],
            'price' => $product['price'],
            'quantity' => $quantity,
            'image_name' => $product['image_name'],
            'total' => $item_total
        ];

 
        $new_stock = $product['stock'] - $quantity;
        $update_stmt = $conn->prepare("UPDATE product SET stock = ? WHERE id = ?");
        $update_stmt->bind_param("ii", $new_stock, $product_id);
        $update_stmt->execute();

        header("Location: cart.php");  
        exit();
    } else {
        echo "❌ Sorry, the requested quantity is not available. Available stock: " . $product['stock'];
    }
} else {
    echo "❌ Product not found.";
}
?>