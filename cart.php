<?php
session_start();
include "db_connection.php"; 
$cart = $_SESSION['cart'] ?? []; 
$messages = ''; 
$cart_valid = true; 
$total_order_price = 0; 
foreach ($cart as $product_id => $item) {
    $stmt = $conn->prepare("SELECT stock FROM product WHERE id = ?");
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $product = $result->fetch_assoc();

    if ($product) {
        $available_stock = $product['stock'];
        if ($item['quantity'] > $available_stock) {
            $messages .= "<div class='alert alert-warning'>⚠️ Not enough stock for <strong>" . htmlspecialchars($item['title']) . "</strong>. Only <strong>$available_stock</strong> available.</div>";
            $cart_valid = false;
        } else {
            $title = $item['title'] ?? 'Unknown';
            $price = $item['price'] ?? 0;
            $quantity = $item['quantity'] ?? 1;
            $item_total = $price * $quantity;
            $total_order_price += $item_total;
        }
    } else {
        $messages .= "<div class='alert alert-danger'>❌ Product <strong>" . htmlspecialchars($item['title']) . "</strong> not found in stock.</div>";
        $cart_valid = false;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Shopping Cart | Bookish</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="./css/style.css">
  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background-color: #f9f9f9;
      margin: 0;
      padding: 0;
    }

    .cart-container {
      max-width: 1200px;
      margin: 2rem auto;
      padding: 1rem 2rem;
      background: #fff;
      border-radius: 16px;
      box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
    }

    .cart-container h2 {
      text-align: center;
      margin-bottom: 2rem;
      color: #e91e63;
      font-weight: 600;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-bottom: 2rem;
    }

    table th, table td {
      padding: 1rem;
      text-align: center;
      border-bottom: 1px solid #eee;
    }

    table th {
      background-color: #e91e63;
      color: #fff;
      font-weight: 600;
    }

    table tr:nth-child(even) {
      background-color: #fef0f5;
    }

    table tr:hover {
      background-color: #fdd0e0;
    }

    .btn {
      color: #fff;
      padding: 10px 20px;
      text-decoration: none;
      border-radius: 8px;
      font-size: 1rem;
      transition: all 0.3s ease;
      border: none;
      cursor: pointer;
    }

    .btn:hover {
      transform: scale(1.05);
    }

    .update-btn {
      background-color: #03a9f4;
    }

    .delete-btn {
      background-color: #e91e63;
    }

    .buy-btn {
      background-color: #4caf50;
    }

    .back-btn {
      background-color: #ba68c8;
    }

    .delete-btn:hover {
      background-color: #c2185b;
    }

    .buy-btn:hover {
      background-color: #388e3c;
    }

    .back-btn:hover {
      background-color: #9c27b0;
    }

    .total {
      font-size: 1.5rem;
      font-weight: 600;
      text-align: right;
      color: #e91e63;
    }

    .alert {
      padding: 15px;
      margin-bottom: 20px;
      border-radius: 8px;
      font-size: 1rem;
      text-align: center;
    }

    .alert-warning {
      background-color: #fff3cd;
      color: #856404;
      border: 1px solid #ffeeba;
    }

    .alert-danger {
      background-color: #f8d7da;
      color: #721c24;
      border: 1px solid #f5c6cb;
    }

    .disabled {
      background-color: #ccc !important;
      cursor: not-allowed !important;
    }

    .product-image {
      width: 60px;
      height: 60px;
      object-fit: cover;
      border-radius: 12px;
      margin-right: 15px;
    }

    .product-title {
      display: flex;
      align-items: center;
      justify-content: start;
    }

    .cart-actions {
      display: flex;
      flex-wrap: wrap;
      gap: 1rem;
      justify-content: space-between;
      margin-top: 2rem;
    }

    @media (max-width: 768px) {
      table, .cart-actions {
        display: block;
        width: 100%;
      }

      .product-title {
        flex-direction: column;
        align-items: flex-start;
      }

      .cart-actions {
        flex-direction: column;
        align-items: stretch;
      }
    }
  </style>
</head>
<body>
  <div class="cart-container">
    <h2>Your Shopping Cart</h2>

    <?= $messages ?>

    <?php if (empty($cart)): ?>
      <p style="text-align: center; font-size: 1.2rem;">Your cart is empty.</p>
      <a href="products.php" class="btn back-btn"><i class="fa fa-arrow-left"></i> Back to Shopping</a>
    <?php else: ?>
      <table>
        <tr>
          <th>Product</th>
          <th>Price</th>
          <th>Quantity</th>
          <th>Total</th>
          <th>Action</th>
        </tr>
        <?php 
          $total = 0;
          foreach ($cart as $index => $item): 
            $item_total = $item['price'] * $item['quantity']; 
            $total += $item_total; 
        ?>
        <tr>
          <td class="product-title">
            <img src="images/<?= htmlspecialchars($item['image_name'] ?? 'default.png') ?>" 
                 alt="<?= htmlspecialchars($item['title'] ?? 'Unknown') ?>" 
                 class="product-image">
            <?= htmlspecialchars($item['title']) ?>
          </td>
          <td>SAR <?= number_format($item['price'], 2) ?></td>
          <td>
            <form method="post" action="update_cart.php" style="display: inline;">
              <input type="hidden" name="index" value="<?= $index ?>">
              <input type="number" name="quantity" value="<?= $item['quantity'] ?>" min="1" style="width: 60px; padding: 5px;">
              <button type="submit" class="btn update-btn">Update</button>
            </form>
          </td>
          <td>SAR <?= number_format($item_total, 2) ?></td>
          <td>
            <form method="post" action="remove_from_cart.php" style="display: inline;">
              <input type="hidden" name="index" value="<?= $index ?>">
              <button type="submit" class="btn delete-btn">Remove</button>
            </form>
          </td>
        </tr>
        <?php endforeach; ?>
      </table>

      <div class="total">Total: SAR <?= number_format($total, 2) ?></div>

      <div class="cart-actions">
        <form method="post" action="clear_cart.php">
          <button type="submit" class="btn delete-btn">Delete All</button>
        </form>

        <form method="post" action="checkout.php">
          <button type="submit" name="buy_now" class="btn buy-btn" <?= empty($cart) ? 'disabled class="disabled"' : '' ?>>Buy Now</button>
        </form>

        <a href="products.php" class="btn back-btn"><i class="fa fa-arrow-left"></i> Back to Shopping</a>
      </div>
    <?php endif; ?>
  </div>
</body>
</html>
