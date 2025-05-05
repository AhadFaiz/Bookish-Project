<?php
session_start();

if (!isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
    header('Location: login.php'); 
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id'])) {
    include "db_connection.php";

    $product_id = intval($_POST['product_id']); 

 
    $query = "DELETE FROM product WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $product_id);

    if ($stmt->execute()) {
       
        header('Location: products.php?message=Product deleted successfully');
        exit;
    } else {
      
        echo "Error deleting product: " . $conn->error;
    }

    $stmt->close();
    $conn->close();
} else {
   
    header('Location: products.php?error=Invalid request');
    exit;
}
?>